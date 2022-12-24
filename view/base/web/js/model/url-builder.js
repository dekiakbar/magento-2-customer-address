/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define(['jquery'], function ($) {
    'use strict';
    let checkoutConfig = window.checkoutConfig;

    return {
        method: 'rest',
        storeCode: checkoutConfig ? checkoutConfig.storeCode : 'default',
        version: 'V1',
        serviceUrl: ':method/:storeCode/:version',
        baseUrl: window.BASE_URL,

        /**
         * @param {String} url
         * @param {Object} params
         * @return {*}
         */
        createUrl: function (url, params) {
            var completeUrl = this.serviceUrl + url;
            return this.bindParams(completeUrl, params);
        },

        /**
         * @param {String} url
         * @param {Object} params
         * @return {*}
         */
        bindParams: function (url, params) {
            var urlParts;

            params.method = this.method;
            params.storeCode = this.storeCode;
            params.version = this.version;

            urlParts = url.split('/');
            urlParts = urlParts.filter(Boolean);

            $.each(urlParts, function (key, part) {
                part = part.replace(':', '');

                if (params[part] != undefined) {
                    urlParts[key] = params[part];
                }
            });

            return this.baseUrl + urlParts.join('/');
        }
    };
});
