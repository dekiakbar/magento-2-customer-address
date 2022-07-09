
/**
 * Copyright © Deki. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'Magento_Ui/js/form/element/abstract',
    'uiRegistry',
    'Deki_CustomerAddress/js/customer/address/fix-jquery-menu',
    'jquery',
    'jquery/ui'
], function (Abstract, uiRegistry, menuPatch ,$) {
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
            var config = window.customerAddressConfig;
            var postCode = uiRegistry.get(this.parentName)
                .getChild(this.postCodeIndex);
            var city = this;

            $("#"+this.uid).autocomplete({
                source: function( request, response ) {
                    if(isNaN(regionId) === false){
                        $.ajax( {
                            url: config.customerAddressUrl,
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
                    $('.ui-front.ui-menu').width($(event.target).outerWidth());
                },
                select: function (event, ui) {
                    event.preventDefault();
                    city.value(ui.item.value);
                    if(config.enablePostcode){
                        postCode.value(ui.item.postcode);
                    }
                },
                change: function (event, ui) {
                    if(config.isForceCityEnabled){
                        if (ui.item === null) {
                            city.value('');
                            postCode.value('');
                        }
                    }
                },
                minLength: config.minSearchLength
            });
        }
    });
});