<?php
/**
 * Copyright © Deki All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\CustomerAddress\Plugin\Frontend\Magento\Checkout\Block\Checkout;

class LayoutProcessor
{
    /**
     * @var Deki\CustomerAddress\Helper\config
     */
    protected $configHelper;

    /**
     * @param Deki\CustomerAddress\Helper\config $configHelper
     */
    public function __construct(
        \Deki\CustomerAddress\Helper\config $configHelper
    ) {
        $this->configHelper = $configHelper;
    }

    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        $result,
        $jsLayout
    ) {
        
        if ($this->configHelper->isEnabled()) {
            $result['components']['checkout']['children']['steps']['children']['shipping-step']
                ['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['city']
                ['component'] ='Deki_CustomerAddress/js/checkout/address/city-autocomplete';
    
            $result['components']['checkout']['children']['steps']['children']['shipping-step']
                ['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['city']
                ['config']['elementTmpl'] ='Deki_CustomerAddress/checkout/address/city-autocomplete';
        }

        return $result;
    }
}
