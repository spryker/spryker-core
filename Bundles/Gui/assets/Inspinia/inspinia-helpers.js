/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved. 
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file. 
 */

'use strict';

function removeStyleAttribute(elementId) {
    setTimeout(function() {
        $(elementId).removeAttr('style');
    }, 500);
}

function sideMenuFadeIn() {
    $('#side-menu').fadeIn(500);
}

module.exports = {
    // Full height of sidebar
    fix_height: function() {
        var heightWithoutNavbar = $("body > #wrapper").height() - 61;
        $(".sidebard-panel").css("min-height", heightWithoutNavbar + "px");

        var navbarHeigh = $('nav.navbar-default').height();
        var wrapperHeigh = $('#page-wrapper').height();

        if (navbarHeigh > wrapperHeigh) {
            $('#page-wrapper').css("min-height", navbarHeigh + "px");
        }

        if (navbarHeigh < wrapperHeigh) {
            $('#page-wrapper').css("min-height", $(window).height() + "px");
        }

        if ($('body').hasClass('fixed-nav')) {
            $('#page-wrapper').css("min-height", $(window).height() - 60 + "px");
        }
    },

    fixBodyClassByResolution: function() {
        if ($(document).width() < 769) {
            $('body').addClass('body-small')
        } else {
            $('body').removeClass('body-small')
        }
    },

    // check if browser support HTML5 local storage
    localStorageSupport: function() {
        return (('localStorage' in window) && window['localStorage'] !== null)
    },

    // For demo purpose - animation css script
    animationHover: function(element, animation) {
        element = $(element);
        element.hover(
            function() {
                element.addClass('animated ' + animation);
            },
            function() {
                //wait for animation to finish before removing classes
                window.setTimeout(function() {
                    element.removeClass('animated ' + animation);
                }, 2000);
            });
    },

    SmoothlyMenu: function() {
        if (!$('body').hasClass('mini-navbar') || $('body').hasClass('body-small')) {
            // Hide menu in order to smoothly turn on when maximize menu
            $('#side-menu').hide();
            // For smoothly turn on menu
            setTimeout(sideMenuFadeIn, 100);
            removeStyleAttribute('#side-menu');
        } else if ($('body').hasClass('fixed-sidebar')) {
            $('#side-menu').hide();
            setTimeout(sideMenuFadeIn, 300);
        } else {
            // Remove all inline style from jquery fadeIn function to reset menu state
            removeStyleAttribute('#side-menu');
        }
    },

    // Dragable panels
    WinMove: function() {
        var element = "[class*=col]";
        var handle = ".ibox-title";
        var connect = "[class*=col]";
        $(element)
            .sortable({
                handle: handle,
                connectWith: connect,
                tolerance: 'pointer',
                forcePlaceholderSize: true,
                opacity: 0.8
            })
            .disableSelection();
    }
};
