
define([
    'Magento_Ui/js/form/element/abstract',
    'mage/url',
    'ko',
    'Magento_Checkout/js/model/quote',
    'jquery',
    'jquery/ui'
], function (Abstract, urlbuild, ko, quote, $) {
    'use strict';
    ko.bindingHandlers.cityAutoComplete = {
        init: function (element, valueAccessor) {
            var settings = valueAccessor();
            var selectedOption = settings.selected;
            var options = settings.options;
            var postcodeEnabled = window.checkoutConfig.customerAddress.postcodeEnabled;
            var minimunSearcLength = window.checkoutConfig.customerAddress.minimunSearcLength;
            var updateElementValueWithLabel = function (event, ui) {
                event.preventDefault();
                $(element).val(ui.item.label);
                if(typeof ui.item !== "undefined") {
                    selectedOption(ui.item);
                }
            };

            $(element).autocomplete({
                source: options,
                select: function (event, ui) {
                    updateElementValueWithLabel(event, ui);
                    if(postcodeEnabled){
                        $("[name='shippingAddress.postcode'] input[name='postcode']").val(ui.item.postcode).trigger('change');
                    }
                },
                open: function(event, ui) {
                    $('.ui-menu').width($(event.target).outerWidth());
                },
                minLength: minimunSearcLength
            });
        }
    };

    return Abstract.extend({
        selectedCity: ko.observable(''),
        getCities: function( request, response ) {
            var regionId = quote.shippingAddress().regionId;
            if(isNaN(regionId) === false){
                $.ajax( {
                    url: urlbuild.build('customeraddress/city/search'),
                    dataType: "json",
                    data: {
                        query: request.term,
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
        }
    });
});