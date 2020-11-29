<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace SoftCommerce\P2p\Logger;

use Magento\Framework\Filesystem\DriverInterface;
use Magento\Framework\Logger\Handler\Base;

/**
 * Class Handler
 * @package SoftCommerce\P2p\Logger
 */
class Handler extends Base
{
    /**
     * @var string
     */
    protected $fileName = '/var/log/softcommerce/p2p.log';

    /**
     * Handler constructor.
     * @param DriverInterface $filesystem
     * @param string|null $filePath
     * @param string|null $fileName
     * @throws \Exception
     */
    public function __construct(DriverInterface $filesystem, ?string $filePath = null, ?string $fileName = null)
    {
        parent::__construct($filesystem, $filePath, $fileName);
    }
}
