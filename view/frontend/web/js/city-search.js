define([
  'jquery',
  'mage/url',
  'jquery/ui'
], function ($,urlbuild) {
    'use strict';

    $.widget('namespace.widgetname', {
        options: {
            autocomplete: 'off',
            minSearchLength: 3,
        },
        _create: function() {
            this._initElement();
        },
        _initElement: function () {
            this.element.attr('autocomplete', this.options.autocomplete);
            var that = this;
            $(this.element).autocomplete({
                source: function( request, response ) {
                    var regionId = $("#region_id").val();
                    if(regionId){
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
                },
                open: function(event, ui) {
                    $('.ui-menu').width($(event.target).outerWidth());
                },
                select: function (event, ui) {
                    if(that.options.enablePostcode){
                        $("input#zip").val(ui.item.postcode);
                    }
                },
                minLength: that.options.minLength
            });
        }
    });

    return $.namespace.widgetname;
});