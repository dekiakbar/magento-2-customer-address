/*!
 * jQuery UI Menu 1.13.0
 * http://jqueryui.com
 *
 * Copyright jQuery Foundation and other contributors
 * Released under the MIT license.
 * http://jquery.org/license
 * 
 * fix conflict jquery menu widget for admin.
 * this file from lib/web/jquery/ui-modules/widgets/menu.js
 * possiblle conflict with lib/web/mage/backend/menu.js
 * 
 * TODO: best practice to fix this ?
 */

define([
    'jquery',
    'jquery/ui'
], function ($) {
    'use strict';

    $.widget( "ui.menu", {
        widgetEventPrefix: 'menu',
        version: "1.13.0",
        defaultElement: "<ul>",
        delay: 300,
        options: {
            icons: {
                submenu: "ui-icon-caret-1-e"
            },
            items: "> *",
            menus: "ul",
            position: {
                my: "left top",
                at: "right top"
            },
            role: "menu",

            // Callbacks
            blur: null,
            focus: null,
            select: null
        },

        _create: function() {
            this.activeMenu = this.element;
    
            // Flag used to prevent firing of the click handler
            // as the event bubbles up through nested menus
            this.mouseHandled = false;
            this.lastMousePosition = { x: null, y: null };
            this.element
                .uniqueId()
                .attr( {
                    role: this.options.role,
                    tabIndex: 0
                } );
    
            this._addClass( "ui-menu", "ui-widget ui-widget-content" );
            this._on( {
    
                // Prevent focus from sticking to links inside menu after clicking
                // them (focus should always stay on UL during navigation).
                "mousedown .ui-menu-item": function( event ) {
                    event.preventDefault();
    
                    this._activateItem( event );
                },
                "click .ui-menu-item": function( event ) {
                    var target = $( event.target );
                    var active = $( $.ui.safeActiveElement( this.document[ 0 ] ) );
                    if ( !this.mouseHandled && target.not( ".ui-state-disabled" ).length ) {
                        this.select( event );
    
                        // Only set the mouseHandled flag if the event will bubble, see #9469.
                        if ( !event.isPropagationStopped() ) {
                            this.mouseHandled = true;
                        }
    
                        // Open submenu on click
                        if ( target.has( ".ui-menu" ).length ) {
                            this.expand( event );
                        } else if ( !this.element.is( ":focus" ) &&
                                active.closest( ".ui-menu" ).length ) {
    
                            // Redirect focus to the menu
                            this.element.trigger( "focus", [ true ] );
    
                            // If the active item is on the top level, let it stay active.
                            // Otherwise, blur the active item since it is no longer visible.
                            if ( this.active && this.active.parents( ".ui-menu" ).length === 1 ) {
                                clearTimeout( this.timer );
                            }
                        }
                    }
                },
                "mouseenter .ui-menu-item": "_activateItem",
                "mousemove .ui-menu-item": "_activateItem",
                mouseleave: "collapseAll",
                "mouseleave .ui-menu": "collapseAll",
                focus: function( event, keepActiveItem ) {
    
                    // If there's already an active item, keep it active
                    // If not, activate the first item
                    var item = this.active || this._menuItems().first();
    
                    if ( !keepActiveItem ) {
                        this.focus( event, item );
                    }
                },
                blur: function( event ) {
                    this._delay( function() {
                        var notContained = !$.contains(
                            this.element[ 0 ],
                            $.ui.safeActiveElement( this.document[ 0 ] )
                        );
                        if ( notContained ) {
                            this.collapseAll( event );
                        }
                    } );
                },
                keydown: "_keydown"
            } );
    
            this.refresh();
    
            // Clicks outside of a menu collapse any open menus
            this._on( this.document, {
                click: function( event ) {
                    if ( this._closeOnDocumentClick( event ) ) {
                        this.collapseAll( event, true );
                    }
    
                    // Reset the mouseHandled flag
                    this.mouseHandled = false;
                }
            } );
        },

        refresh: function() {
            var menus, items, newSubmenus, newItems, newWrappers,
                that = this,
                icon = this.options.icons.submenu,
                submenus = this.element.find( this.options.menus );
    
            this._toggleClass( "ui-menu-icons", null, !!this.element.find( ".ui-icon" ).length );
    
            // Initialize nested menus
            newSubmenus = submenus.filter( ":not(.ui-menu)" )
                .hide()
                .attr( {
                    role: this.options.role,
                    "aria-hidden": "true",
                    "aria-expanded": "false"
                } )
                .each( function() {
                    var menu = $( this ),
                        item = menu.prev(),
                        submenuCaret = $( "<span>" ).data( "ui-menu-submenu-caret", true );
    
                    that._addClass( submenuCaret, "ui-menu-icon", "ui-icon " + icon );
                    item
                        .attr( "aria-haspopup", "true" )
                        .prepend( submenuCaret );
                    menu.attr( "aria-labelledby", item.attr( "id" ) );
                } );
    
            this._addClass( newSubmenus, "ui-menu", "ui-widget ui-widget-content ui-front" );
    
            menus = submenus.add( this.element );
            items = menus.find( this.options.items );
    
            // Initialize menu-items containing spaces and/or dashes only as dividers
            items.not( ".ui-menu-item" ).each( function() {
                var item = $( this );
                if ( that._isDivider( item ) ) {
                    that._addClass( item, "ui-menu-divider", "ui-widget-content" );
                }
            } );
    
            // Don't refresh list items that are already adapted
            newItems = items.not( ".ui-menu-item, .ui-menu-divider" );
            newWrappers = newItems.children()
                .not( ".ui-menu" )
                    .uniqueId()
                    .attr( {
                        tabIndex: -1,
                        role: this._itemRole()
                    } );
            this._addClass( newItems, "ui-menu-item" )
                ._addClass( newWrappers, "ui-menu-item-wrapper" );
    
            // Add aria-disabled attribute to any disabled menu item
            items.filter( ".ui-state-disabled" ).attr( "aria-disabled", "true" );
    
            // If the active item has been removed, blur the menu
            if ( this.active && !$.contains( this.element[ 0 ], this.active[ 0 ] ) ) {
                this.blur();
            }
        },

        collapseAll: function( event, all ) {
            clearTimeout( this.timer );
            this.timer = this._delay( function() {
    
                // If we were passed an event, look for the submenu that contains the event
                var currentMenu = all ? this.element :
                    $( event && event.target ).closest( this.element.find( ".ui-menu" ) );
    
                // If we found no valid submenu ancestor, use the main menu to close all
                // sub menus anyway
                if ( !currentMenu.length ) {
                    currentMenu = this.element;
                }
    
                this._close( currentMenu );
    
                this.blur( event );
    
                // Work around active item staying active after menu is blurred
                this._removeClass( currentMenu.find( ".ui-state-active" ), null, "ui-state-active" );
    
                this.activeMenu = currentMenu;
            }, all ? 0 : this.delay );
        },

        _isDivider: function( item ) {

            // Match hyphen, em dash, en dash
            return !/[^\-\u2014\u2013\s]/.test( item.text() );
        },

        _itemRole: function() {
            return {
                menu: "menuitem",
                listbox: "option"
            }[ this.options.role ];
        },

        // With no arguments, closes the currently active menu - if nothing is active
        // it closes all menus.  If passed an argument, it will search for menus BELOW
        _close: function( startMenu ) {
            if ( !startMenu ) {
                startMenu = this.active ? this.active.parent() : this.element;
            }

            startMenu.find( ".ui-menu" )
                .hide()
                .attr( "aria-hidden", "true" )
                .attr( "aria-expanded", "false" );
        },

        _activateItem: function( event ) {

            // Ignore mouse events while typeahead is active, see #10458.
            // Prevents focusing the wrong item when typeahead causes a scroll while the mouse
            // is over an item in the menu
            if ( this.previousFilter ) {
                return;
            }
    
            // If the mouse didn't actually move, but the page was scrolled, ignore the event (#9356)
            if ( event.clientX === this.lastMousePosition.x &&
                    event.clientY === this.lastMousePosition.y ) {
                return;
            }
    
            this.lastMousePosition = {
                x: event.clientX,
                y: event.clientY
            };
    
            var actualTarget = $( event.target ).closest( ".ui-menu-item" ),
                target = $( event.currentTarget );
    
            // Ignore bubbled events on parent items, see #11641
            if ( actualTarget[ 0 ] !== target[ 0 ] ) {
                return;
            }
    
            // If the item is already active, there's nothing to do
            if ( target.is( ".ui-state-active" ) ) {
                return;
            }
    
            // Remove ui-state-active class from siblings of the newly focused menu item
            // to avoid a jump caused by adjacent elements both having a class with a border
            this._removeClass( target.siblings().children( ".ui-state-active" ),
                null, "ui-state-active" );
            this.focus( event, target );
        },

        select: function( event ) {

            // TODO: It should never be possible to not have an active item at this
            // point, but the tests don't trigger mouseenter before click.
            this.active = this.active || $( event.target ).closest( ".ui-menu-item" );
            var ui = { item: this.active };
            if ( !this.active.has( ".ui-menu" ).length ) {
                this.collapseAll( event, true );
            }
            this._trigger( "select", event, ui );
        },

        _closeOnDocumentClick: function( event ) {
            return !$( event.target ).closest( ".ui-menu" ).length;
        },

        blur: function( event, fromFocus ) {
            if ( !fromFocus ) {
                clearTimeout( this.timer );
            }
    
            if ( !this.active ) {
                return;
            }
    
            this._removeClass( this.active.children( ".ui-menu-item-wrapper" ),
                null, "ui-state-active" );
    
            this._trigger( "blur", event, { item: this.active } );
            this.active = null;
        },

        focus: function( event, item ) {
            var nested, focused, activeParent;
            this.blur( event, event && event.type === "focus" );
    
            this._scrollIntoView( item );
    
            this.active = item.first();
    
            focused = this.active.children( ".ui-menu-item-wrapper" );
            this._addClass( focused, null, "ui-state-active" );
    
            // Only update aria-activedescendant if there's a role
            // otherwise we assume focus is managed elsewhere
            if ( this.options.role ) {
                this.element.attr( "aria-activedescendant", focused.attr( "id" ) );
            }
    
            // Highlight active parent menu item, if any
            activeParent = this.active
                .parent()
                    .closest( ".ui-menu-item" )
                        .children( ".ui-menu-item-wrapper" );
            this._addClass( activeParent, null, "ui-state-active" );
    
            if ( event && event.type === "keydown" ) {
                this._close();
            } else {
                this.timer = this._delay( function() {
                    this._close();
                }, this.delay );
            }
    
            nested = item.children( ".ui-menu" );
            if ( nested.length && event && ( /^mouse/.test( event.type ) ) ) {
                this._startOpening( nested );
            }
            this.activeMenu = item.parent();
    
            this._trigger( "focus", event, { item: item } );
        },

        _scrollIntoView: function( item ) {
            var borderTop, paddingTop, offset, scroll, elementHeight, itemHeight;
            if ( this._hasScroll() ) {
                borderTop = parseFloat( $.css( this.activeMenu[ 0 ], "borderTopWidth" ) ) || 0;
                paddingTop = parseFloat( $.css( this.activeMenu[ 0 ], "paddingTop" ) ) || 0;
                offset = item.offset().top - this.activeMenu.offset().top - borderTop - paddingTop;
                scroll = this.activeMenu.scrollTop();
                elementHeight = this.activeMenu.height();
                itemHeight = item.outerHeight();
    
                if ( offset < 0 ) {
                    this.activeMenu.scrollTop( scroll + offset );
                } else if ( offset + itemHeight > elementHeight ) {
                    this.activeMenu.scrollTop( scroll + offset - elementHeight + itemHeight );
                }
            }
        },

        _hasScroll: function() {
            return this.element.outerHeight() < this.element.prop( "scrollHeight" );
        },
    });

    return $.ui.menu;
});
