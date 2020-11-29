<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace SoftCommerce\P2p\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Data
 * @package SoftCommerce\MintSoft\Helper
 */
class Data extends AbstractHelper
{
    const XML_PATH_IS_ACTIVE                    = 'softcommerce_p2p/general/is_active';
    const XML_PATH_API_NAME                     = 'softcommerce_p2p/client/api_name';
    const XML_PATH_API_URL                      = 'softcommerce_p2p/client/api_url';
    const XML_PATH_API_KEY                      = 'softcommerce_p2p/client/api_key';
    const XML_PATH_API_RETRY                    = 'softcommerce_p2p/client/api_retry';
    const XML_PATH_API_CONNECTION_TIMEOUT       = 'softcommerce_p2p/client/api_connection_timeout';
    const XML_PATH_API_TIMEOUT                  = 'softcommerce_p2p/client/api_timeout';
    const XML_PATH_DEV_IS_ACTIVE_DEBUG          = 'softcommerce_p2p/dev/is_active_debug';
    const XML_PATH_DEV_DEBUG_PRINT_TO_ARRAY     = 'softcommerce_p2p/dev/debug_print_to_array';

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $_storeManager;

    /**
     * @var EncryptorInterface
     */
    private EncryptorInterface $_encryptor;

    /**
     * Data constructor.
     * @param Context $context
     * @param EncryptorInterface $encryptor
     * @param StoreManagerInterface $storeManager
     * @param DateTime $date
     * @param TimezoneInterface $timezone
     */
    public function __construct(
        Context $context,
        EncryptorInterface $encryptor,
        StoreManagerInterface $storeManager,
        DateTime $date,
        TimezoneInterface $timezone
    ) {
        $this->_storeManager = $storeManager;
        $this->_encryptor = $encryptor;
        parent::__construct($context);
    }

    /**
     * @return int
     * @throws NoSuchEntityException
     */
    protected function _getStore() : int
    {
        return $this->_storeManager->getStore()->getId();
    }

    /**
     * @param $path
     * @param null $store
     * @return mixed
     * @throws NoSuchEntityException
     */
    protected function _getConfig($path, $store = null)
    {
        if (null === $store) {
            $store = $this->_getStore();
        }

        return $this->scopeConfig
            ->getValue($path, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * @return bool
     * @throws NoSuchEntityException
     */
    public function getIsActive() : bool
    {
        return (bool) $this->_getConfig(self::XML_PATH_IS_ACTIVE);
    }

    /**
     * @return string|null
     * @throws NoSuchEntityException
     */
    public function getApiName() : ?string
    {
        return $this->_getConfig(self::XML_PATH_API_NAME);
    }

    /**
     * @param null|string $route
     * @return string
     * @throws NoSuchEntityException
     */
    public function getApiUrl($route = null) : ?string
    {
        return null === $route
            ? $this->_getConfig(self::XML_PATH_API_URL)
            : $this->_getConfig(self::XML_PATH_API_URL) . $route;
    }

    /**
     * @return string|null
     * @throws NoSuchEntityException
     */
    public function getApiKey() : ?string
    {
        return $this->_encryptor->decrypt(
            $this->_getConfig(self::XML_PATH_API_KEY)
        );
    }

    /**
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getApiRetries() : ?int
    {
        return $this->_getConfig(self::XML_PATH_API_RETRY);
    }

    /**
     * @return int|null
     * @throws NoSuchEntityException
     */
    public function getApiConnectionTimeout() : ?int
    {
        return $this->_getConfig(self::XML_PATH_API_CONNECTION_TIMEOUT);
    }

    /**
     * @return int|null
     * @throws NoSuchEntityException
     */
    public function getApiTimeout() : ?int
    {
        return $this->_getConfig(self::XML_PATH_API_TIMEOUT);
    }

    /**
     * @return mixed
     */
    public function getIsActiveDebug() : bool
    {
        return (bool) $this->scopeConfig->isSetFlag(self::XML_PATH_DEV_IS_ACTIVE_DEBUG);
    }

    /**
     * @return bool
     */
    public function getIsDebugPrintToArray() : bool
    {
        return (bool) $this->scopeConfig->isSetFlag(self::XML_PATH_DEV_DEBUG_PRINT_TO_ARRAY);
    }
}
