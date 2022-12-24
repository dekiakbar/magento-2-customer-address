<?php
/**
 * Copyright © Deki All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\CustomerAddress\Block\Checkout;

use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use Magento\Checkout\Helper\Data;

class LayoutProcessor implements LayoutProcessorInterface
{
    public const CITY_COMPONENT = 'Deki_CustomerAddress/js/checkout/address/city-autocomplete';
    public const CITY_ELEMENT_TEMPLATE = 'Deki_CustomerAddress/form/element/select';

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
     * @param array $jsLayout
     * @return array
     */
    public function process($jsLayout)
    {
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
                $paymentMethodLayouts[$code]['children']['form-fields']
                    ['children']['city']['config']['elementTmpl'] = self::CITY_ELEMENT_TEMPLATE;
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
            $afterMethodsLayout['billing-address-form']['children']['form-fields']
                ['children']['city']['config']['elementTmpl'] = self::CITY_ELEMENT_TEMPLATE;
        }

        return $afterMethodsLayout;
    }
}
