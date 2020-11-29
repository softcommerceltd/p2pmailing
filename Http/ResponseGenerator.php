<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace SoftCommerce\P2p\Http;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class RequestGenerator
 * @package SoftCommerce\P2p\Http
 */
class ResponseGenerator implements ResponseGeneratorInterface
{
    /**
     * @var Json|null
     */
    private ?Json $_serializer;

    /**
     * ResponseGenerator constructor.
     * @param Json|null $serializer
     */
    public function __construct(
        ?Json $serializer = null
    ) {
        $this->_serializer = $serializer ?: ObjectManager::getInstance()->get(Json::class);
    }

    /**
     * @param \SimpleXMLElement $responseXml
     * @return array|array[]
     */
    public function getTrackShipment(\SimpleXMLElement $responseXml) : array
    {
        try {
            $result = $this->_serializer->unserialize(
                $this->_serializer->serialize($responseXml->Shipment ?: $responseXml)
            );
        } catch (\InvalidArgumentException $e) {
            $result = [];
        }

        return $result && is_array($result)
            ? $result
            : ($result ? [$result] : []);
    }
}
