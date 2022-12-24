/**
 * Copyright © Deki. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
  'jquery',
  'Deki_CustomerAddress/js/model/url-builder',
  'underscore',
  'select2',
  'mage/mage',
], function ($, urlBuilder, _) {
    'use strict';

    $.widget('city.SearchAutocomplete', {
        options:{
            url : urlBuilder.createUrl('/customer-address/autocomplete', {}),
            placeholder: "Please select a city"
        },

        /**
         * Do initial select2 in city element
         */
        _create: function() {
            var self = this;
            
            $(this.options.countryId).on('change', function(e){
                let countriId = $(e.target).val();
                if (self.options.regionJson[countriId]) {
                    self._initAjaxSelect();
                }else{
                    self._initSelect();
                }
            });
            console.log(
                this.options.initialCountryId
            );
            if (this.options.regionJson[this.options.initialCountryId]) {
                this._initAjaxSelect();
            }else{
                this._initSelect();
            }
            
        },

        /**
         * Initial select2 js element
         */
        _initAjaxSelect: function () {
            var self = this;
            
            $(this.element).select2({
                minimumInputLength: this.options.minLength,
                multiple: false,
                ajax: {
                    url: this.options.url,
                    dataType: 'json',
                    delay: 500,
                    data: function (params) {
                        let regionListEl = $(self.options.regionListId);
                        let regionId = regionListEl.val();

                        let query = {
                            regionId: regionId,
                            query: params.term,
                        }

                        return query;
                    },
                    processResults: function (response) {
                        let datas = $.map(response.items, function (city) {
                            return {
                                id: city.name,
                                text: city.name,
                                postcode: city.postcode
                            };
                        });
                        
                        return {
                            results: datas
                        };
                    },
                }
            });

            $(this.element).on('select2:select',function(e){
                let data = e.params.data;
                if(self.options.enablePostcode){
                    if(data.postcode){
                        $(self.options.postcodeId).val(data.postcode).trigger("change");
                    }
                }
            });
        },

        _initSelect: function(){
            $(this.element).select2({
                minimumInputLength: this.options.minLength,
                multiple: false,
                placeholder: this.options.placeholder,
                tags: true
            });
        }
    });

    return $.city.SearchAutocomplete;
});