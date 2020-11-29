<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace SoftCommerce\P2p\Http;

/**
 * Interface RequestGeneratorInterface
 * @package SoftCommerce\P2p\Http
 */
interface RequestGeneratorInterface
{
    /**
     * @param $token
     * @param $packNo
     * @return string
     */
    public function getTrackShipment(string $token, string $packNo) : string;
}
