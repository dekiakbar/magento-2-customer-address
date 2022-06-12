
/**
 * Copyright © Deki. All rights reserved.
 * See COPYING.txt for license details.
 */

 define([
    'Magento_Ui/js/form/element/abstract',
    'uiRegistry',
    'mage/url',
    'jquery',
    'jquery/ui'
], function (Abstract, uiRegistry, urlbuild, $) {
    'use strict';

    return Abstract.extend({
        defaults: {
            skipValidation: false,
            imports: {
                regionInput: '${ $.parentName }.region_id_input:value',
                regionId: '${ $.parentName }.region_id:value',
            },
            postCodeIndex: 'postcode',
            valueUpdate: 'keypress'
        },

        /**
         * Callback that fires when 'value' property is updated.
         */
        onUpdate: function () {
            var regionId = this.regionId;
            var keyword = this.value;
            var postcodeEnabled = window.checkoutConfig.customerAddress.postcodeEnabled;
            var minimunSearcLength = window.checkoutConfig.customerAddress.minimunSearcLength;
            var isForceCityEnabled = window.checkoutConfig.customerAddress.isForceCityEnabled;
            var postCode = uiRegistry.get(this.parentName)
                .getChild(this.postCodeIndex);
            var city = this;

            $("#"+this.uid).autocomplete({
                source: function( request, response ) {
                    if(isNaN(regionId) === false){
                        $.ajax( {
                            url: urlbuild.build('customeraddress/city/search'),
                            dataType: "json",
                            method: "GET",
                            data: {
                                query: keyword,
                                region_id: regionId
                            },
                            success: function( data ) {
                                response($.map( data, function( item ) {
                                    return {
                                        label: item.name,
                                        value: item.name,
                                        postcode: item.postcode
                                    }
                                }));
                            }
                        });
                    }
                },
                open: function(event, ui) {
                    $('.customer-address-autocomplete.ui-front.ui-menu').width($(event.target).outerWidth());
                },
                select: function (event, ui) {
                    if(postcodeEnabled){
                        postCode.value(ui.item.postcode);
                    }
                },
                change: function (event, ui) {
                    if(isForceCityEnabled){
                        if (ui.item === null) {
                            city.value('');
                            postCode.value('');
                        }
                    }
                },
                classes: {
                    "ui-autocomplete": "customer-address-autocomplete",
                }, 
                minLength: minimunSearcLength
            });
        },
    });
});