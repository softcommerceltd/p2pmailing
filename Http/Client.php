<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace SoftCommerce\P2p\Http;

use GuzzleHttp;
use GuzzleHttp\ClientFactory;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ResponseFactory;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Webapi\Rest\Request;
use Psr\Http\Message\StreamInterface;
use SoftCommerce\MintSoft\Helper;
use SoftCommerce\MintSoft\Logger\Logger;

/**
 * Class Client
 * @package SoftCommerce\P2p\Http
 */
class Client implements ClientInterface
{
    /**
     * @var ClientFactory
     */
    private ClientFactory $_clientFactory;

    /**
     * @var Helper\Data
     */
    private Helper\Data $_helper;

    /**
     * @var Logger
     */
    private Logger $_logger;

    /**
     * @var ResponseFactory
     */
    private ResponseFactory $_responseFactory;

    /**
     * @var Response
     */
    private ?Response $_response = null;

    /**
     * @var string|int|null
     */
    private $_responseStatusCode;

    /**
     * @var StreamInterface
     */
    private ?StreamInterface $_responseBody = null;

    /**
     * @var string|null
     */
    private ?string $_responseContents = null;

    /**
     * @var array
     */
    private array $_request = [];

    /**
     * @var string
     */
    private ?string $_requestUri = null;

    /**
     * @var Json
     */
    private ?Json $_serializer;

    /**
     * Client constructor.
     * @param ClientFactory $clientFactory
     * @param ResponseFactory $responseFactory
     * @param Helper\Data $helper
     * @param Logger $logger
     * @param Json|null $serializer
     */
    public function __construct(
        GuzzleHttp\ClientFactory $clientFactory,
        ResponseFactory $responseFactory,
        Helper\Data $helper,
        Logger $logger,
        ?Json $serializer = null
    ) {
        $this->_clientFactory = $clientFactory;
        $this->_responseFactory = $responseFactory;
        $this->_helper = $helper;
        $this->_logger = $logger;
        $this->_serializer = $serializer ?: ObjectManager::getInstance()->get(Json::class);
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->_response ?: $this->_responseFactory->create();
    }

    /**
     * @param Response $response
     * @return $this
     */
    public function setResponse(Response $response)
    {
        $this->_response = $response;
        return $this;
    }

    /**
     * @return string|int|null
     */
    public function getResponseStatusCode()
    {
        return $this->_responseStatusCode;
    }

    /**
     * @param string|int $statusCode
     * @return $this
     */
    public function setResponseStatusCode($statusCode)
    {
        $this->_responseStatusCode = $statusCode;
        return $this;
    }

    /**
     * @return StreamInterface
     */
    public function getResponseBody()
    {
        return $this->_responseBody;
    }

    /**
     * @param StreamInterface $body
     * @return $this
     */
    public function setResponseBody(StreamInterface $body)
    {
        $this->_responseBody = $body;
        return $this;
    }

    /**
     * @param bool $decoded
     * @return array|bool|float|int|mixed|string|null
     */
    public function getResponseContents($decoded = false)
    {
        return false === $decoded
            ? $this->_responseContents
            : $this->_serializer->unserialize($this->_responseContents);
    }

    /**
     * @param string $contents
     * @return $this
     */
    public function setResponseContents(string $contents)
    {
        $this->_responseContents = $contents;
        return $this;
    }

    /**
     * @return \SimpleXMLElement|null
     */
    public function getResponseXml()
    {
        return is_string($this->getResponseContents())
            ? \simplexml_load_string($this->getResponseContents())
            : null;
    }

    /**
     * @return array|string|mixed
     */
    public function getRequest()
    {
        return $this->_request ?: [];
    }

    /**
     * @param string|array|mixed $data
     * @return $this
     */
    public function setRequest($data)
    {
        $this->_request = $data;
        return $this;
    }

    /**
     * @return string
     */
    public function getRequestUri()
    {
        return $this->_requestUri;
    }

    /**
     * @param string $uri
     * @return $this
     */
    public function setRequestUri(string $uri)
    {
        $this->_requestUri = $uri;
        return $this;
    }

    /**
     * @param string $uri
     * @param null $params
     * @param string $method
     * @return $this|Client
     * @throws LocalizedException
     */
    public function execute(
        string $uri,
        $params = null,
        string $method = Request::HTTP_METHOD_POST
    ) {
        $this->executeBefore()
            ->setRequestUri($uri);

        /** @var GuzzleHttp\Client $client */
        $client = $this->_clientFactory->create();

        if (null !== $params) {
            $this->setRequest(
                [
                    GuzzleHttp\RequestOptions::BODY => $params,
                    GuzzleHttp\RequestOptions::HEADERS => [
                        'User-Agent'    => 'magento/2.4.*',
                        'Accept'        => 'application/xml',
                        'Content-Type'  => 'application/xml'
                    ]
                ]
            );
        }

        try {
            $response = $client->request($method, $this->getRequestUri(), $this->getRequest());
        } catch (GuzzleException $e) {
            $this->_log($e->getMessage());
            $response = $this->_responseFactory->create([
                'status' => $e->getCode(),
                'reason' => $e->getMessage()
            ]);
        }

        if (!$body = $response->getBody()) {
            throw new LocalizedException(__('Could not retrieve response body.'));
        }

        if (!$contents = $body->getContents()) {
            throw new LocalizedException(__('Could not retrieve response contents.'));
        }

        $this->setResponse($response)
            ->setResponseStatusCode($response->getStatusCode())
            ->setResponseBody($body)
            ->setResponseContents($contents);

        return $this;
    }

    /**
     * @return $this
     */
    public function executeBefore()
    {
        $this->_response =
        $this->_responseBody =
        $this->_responseContents =
        $this->_responseStatusCode =
        $this->_requestUri =
            null;
        $this->_request = [];

        return $this;
    }

    /**
     * @param $message
     * @param array $context
     * @param bool $force
     * @return $this
     */
    private function _log($message, array $context = [], $force = false)
    {
        if (false === $force || !$this->_helper->getIsActiveDebug()) {
            return $this;
        }

        if ($this->_helper->getIsDebugPrintToArray()) {
            $this->_logger->debug(print_r([$message => $context], true), []);
        } else {
            $this->_logger->debug($message, $context);
        }

        return $this;
    }
}
