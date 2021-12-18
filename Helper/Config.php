<?php
/**
 * Copyright © Deki All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\CustomerAddress\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Config extends AbstractHelper
{
    const ENABLE = 'customeraddress/general/enable';
    const ENABLE_POSTCODE = 'customeraddress/general/enable_postcode';
    const MINIMUM_SEARCH_LENGTH = 'customeraddress/general/minimum_search_length';
    const FORCE_SELECT_CITY = 'customeraddress/general/force_select_city';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context);
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->scopeConfig->getValue(self::ENABLE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return bool
     */
    public function isPostcodeEnabled()
    {
        return $this->scopeConfig->getValue(self::ENABLE_POSTCODE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return int
     */
    public function getMinimunSearcLength()
    {
        return $this->scopeConfig->getValue(self::MINIMUM_SEARCH_LENGTH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return bool
     */
    public function isForceCityEnabled()
    {
        return $this->scopeConfig->getValue(self::FORCE_SELECT_CITY, ScopeInterface::SCOPE_STORE);
    }
}
