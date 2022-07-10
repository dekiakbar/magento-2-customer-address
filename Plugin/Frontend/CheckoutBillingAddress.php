<?php
/**
 * Copyright © Deki All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Deki\CustomerAddress\Plugin\Frontend;

use Magento\Checkout\Block\Checkout\LayoutProcessor;
use Magento\Checkout\Helper\Data;

class CheckoutBillingAddress
{
    const CITY_COMPONENT = 'Deki_CustomerAddress/js/checkout/address/city-autocomplete';

    /**
     * @var Data
     */
    private $_checkoutDataHelper;

    /**
     * @param Data $_checkoutDataHelper
     */
    public function __construct(
        Data $_checkoutDataHelper,
    ) {
        $this->_checkoutDataHelper = $_checkoutDataHelper;
    }

    /**
     * Added autocomplete to city field in
     * billing address form
     * 
     * @param LayoutProcessor $layoutProcessor
     * @param array           $jsLayout
     * 
     * @return array
     * 
     * @throws InputException
     */
    public function afterProcess(
        LayoutProcessor $layoutProcessor,
        $jsLayout
    ) {
        // The if billing address should be displayed on Payment method or page
        if ($this->_checkoutDataHelper->isDisplayBillingOnPaymentMethodAvailable()) {
            $jsLayout['components']['checkout']['children']['steps']['children']
                ['billing-step']['children']['payment']['children']['payments-list']
                ['children'] = $this->addAutocompletePaymentMethod(
                    $jsLayout['components']['checkout']['children']['steps']
                        ['children']['billing-step']['children']['payment']
                        ['children']['payments-list']['children']
                );
        } else {
            $afterMethodsLayout = $jsLayout['components']['checkout']['children']
                ['steps']['children']['billing-step']['children']['payment']
                ['children']['afterMethods']['children'];

            $jsLayout['components']['checkout']['children']['steps']['children']
                ['billing-step']['children']['payment']['children']['afterMethods']
                ['children'] = $this->addAutocompletePaymentPage(
                    $afterMethodsLayout
                );
        }
        
        return $jsLayout;
    }

    /**
     * Add autocomplete if billing in payment method
     * 
     * @param array $paymentMethodLayouts
     * 
     * @return array
     */
    public function addAutocompletePaymentMethod($paymentMethodLayouts)
    {
        foreach ($paymentMethodLayouts as $code => $paymentMethod) {
            if (array_key_exists('form-fields', $paymentMethod['children'])) {
                $paymentMethodLayouts[$code]['children']['form-fields']
                    ['children']['city']['component'] = self::CITY_COMPONENT;
            }
        }

        return $paymentMethodLayouts;
    }

    /**
     * Add autocomplete if billing in payment page
     * 
     * @param array $afterMethodsLayout
     * 
     * @return array
     */
    public function addAutocompletePaymentPage($afterMethodsLayout)
    {
        if (array_key_exists('billing-address-form', $afterMethodsLayout)) {
            $afterMethodsLayout['billing-address-form']['children']['form-fields']
                ['children']['city']['component'] = self::CITY_COMPONENT; 
        }

        return $afterMethodsLayout;
    }
}