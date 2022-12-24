/**
 * Copyright © Deki. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'Magento_Ui/js/form/element/abstract',
    'underscore',
    'uiRegistry',
    'Deki_CustomerAddress/js/model/url-builder',
    'mage/translate',
    'jquery',
    'select2'
], function (Abstract, _, uiRegistry, urlBuilder, $t, $) {
    'use strict';

    return Abstract.extend({
        defaults: {
            skipValidation: false,
            imports: {
                regionId: '${ $.parentName }.region_id:value',
                countryUpdate: '${ $.parentName }.country_id:value',
                regionOptions: '${ $.parentName }.region_id:options',
                postcodeName: '${ $.parentName }.postcode:name'
            },
            url: urlBuilder.createUrl('/customer-address/autocomplete', {}),
            minimumInputLength: window.checkoutConfig.customerAddress.minimunSearcLength,
            postcodeEnabled: window.checkoutConfig.customerAddress.postcodeEnabled,
            searchDelay: 500,
            placeholder: $t("Please select a city")
        },

        /**
         * Re-init city elemtn if cuntry changed.
         * 
         * @param {string} countryId 
         */
        countryUpdate: function(countryId){
            if(_.isEmpty(this.regionOptions)){
                this.initSelect();
            }else{
                this.initAjaxSelect();
            }
        },

        /**
         * Calls 'initObservable' of parent, this will make 
         * getInitialValue() return an selected option
         *
         * @returns {Object} Chainable.
         */
        initObservable: function () {
            this._super();
            this.observe('options value');

            return this;
        },

        /**
         * Init select2 js to KO js select element
         */
        initSelectJs: function(){
            if(_.isEmpty(this.regionOptions)){
                this.initSelect();
            }else{
                this.initAjaxSelect();
            }
            
            if(this.getInitialValue()){
                this.setInitialOption(this.getInitialValue());
            }
        },
        
        /**
         * Set initial option and create if option not exist
         * 
         * @param {string} value 
         */
        setInitialOption: function(value)
        {
            let initialOption = new Option(value, value, true, true);
            $("#"+this.uid).append(initialOption).trigger('change');
        },
        
        /**
         * Init selectJs for country has region options
         */
        initAjaxSelect: function(){
            var self = this;
            $("#"+this.uid).select2({
                placeholder: self.placeholder,
                minimumInputLength: self.minimumInputLength,
                multiple: false,
                width: '100%',
                ajax: {
                    url: self.url,
                    dataType: 'json',
                    delay: self.searchDelay,
                    data: function (params) {
                        let query = {
                            regionId: self.regionId,
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
                    }
                }
            });

            $("#"+this.uid).on('select2:select',function(e){
                let data = e.params.data;
                self.value(data.text);
                if(self.postcodeEnabled){
                    if(data.postcode){
                        let postCode = uiRegistry.get(self.postcodeName);
                        postCode.value(data.postcode).trigger("change");
                    }
                }
            });
        },

        /**
         * Init city for country does not have region options
         */
        initSelect: function(){
            var self = this;
            $("#"+this.uid).select2({
                minimumInputLength: self.minimumInputLength,
                multiple: false,
                placeholder: this.options.placeholder,
                tags: true,
                width: '100%'
            });
        }
    });
});