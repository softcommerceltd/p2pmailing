<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace SoftCommerce\P2p\Http;

/**
 * Class RequestGenerator
 * @package SoftCommerce\P2p\Http
 */
class RequestGenerator implements RequestGeneratorInterface
{
    /**
     * @param $token
     * @param $packNo
     * @return string
     */
    public function getTrackShipment(string $token, string $packNo) : string
    {
        return "<TrackShipment><Apikey>$token</Apikey><Shipment><TrackingNumber>$packNo</TrackingNumber></Shipment></TrackShipment>";
    }
}

