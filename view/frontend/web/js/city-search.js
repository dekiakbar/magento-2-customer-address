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

        _create: function () {
            this.element.attr('autocomplete', this.options.autocomplete);
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
                                        value: item.name
                                    }
                                }));
                                // response( data );
                            }
                        });
                    }
                },
                minLength: 3,
                open: function(event, ui) {
                    $('.ui-menu').width($(event.target).outerWidth());
                },
            });
        }
    });

    return $.namespace.widgetname;
});