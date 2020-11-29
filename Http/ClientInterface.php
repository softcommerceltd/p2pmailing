<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace SoftCommerce\P2p\Http;

use GuzzleHttp\Psr7\Response;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Webapi\Rest\Request;
use Psr\Http\Message\StreamInterface;

/**
 * Interface ClientInterface
 * @package SoftCommerce\P2p\Http
 */
interface ClientInterface
{
    /**
     * HTTP request methods
     */
    const GET                   = 'GET';
    const POST                  = 'POST';
    const PUT                   = 'PUT';
    const DELETE                = 'DELETE';
    const OAUTH                 = 'OAUTH';
    const HEAD                  = 'HEAD';
    const TRACE                 = 'TRACE';
    const OPTIONS               = 'OPTIONS';
    const CONNECT               = 'CONNECT';
    const MERGE                 = 'MERGE';
    const PATCH                 = 'PATCH';

    /**
     * @return Response
     */
    public function getResponse();

    /**
     * @param Response $response
     * @return $this
     */
    public function setResponse(Response $response);

    /**
     * @return string|int|null
     */
    public function getResponseStatusCode();

    /**
     * @param string|int $statusCode
     * @return $this
     */
    public function setResponseStatusCode($statusCode);

    /**
     * @return StreamInterface
     */
    public function getResponseBody();

    /**
     * @param StreamInterface $body
     * @return $this
     */
    public function setResponseBody(StreamInterface $body);

    /**
     * @param bool $decoded
     * @return array|bool|float|int|mixed|string|null
     */
    public function getResponseContents($decoded = false);

    /**
     * @param string $contents
     * @return $this
     */
    public function setResponseContents(string $contents);

    /**
     * @return \SimpleXMLElement|null
     */
    public function getResponseXml();

    /**
     * @return array|string|mixed
     */
    public function getRequest();

    /**
     * @param string|array|mixed $data
     * @return $this
     */
    public function setRequest($data);

    /**
     * @return string
     */
    public function getRequestUri();

    /**
     * @param string $uri
     * @return $this
     */
    public function setRequestUri(string $uri);

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
    );

    /**
     * @return $this
     */
    public function executeBefore();
}
