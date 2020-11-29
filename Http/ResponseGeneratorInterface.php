<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace SoftCommerce\P2p\Http;

/**
 * Interface ResponseGeneratorInterface
 * @package SoftCommerce\P2p\Http
 */
interface ResponseGeneratorInterface
{
    /**
     * @param \SimpleXMLElement $responseXml
     * @return array|array[]
     */
    public function getTrackShipment(\SimpleXMLElement $responseXml) : array;
}
