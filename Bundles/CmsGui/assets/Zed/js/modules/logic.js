/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var summernote = require('ZedGuiEditorConfiguration');

const GLOSSARY_SELECT_MARGIN_WIDTH = 25;

var xhr = null;
var keyList = null;
var keyContainer = null;
var itemContainer = null;

String.prototype.formatString = function()
{
    var args = arguments;
    return this.replace(/{(\d+)}/g, function(match, index){
        return args[index];
    });
};

function showAutoComplete(placeholderTranslationContainer, searchType)
{
    var searchTypeGlossaryKey = 2;
    var searchTypeFullText = 3;
    var listElement = '<div id="foundKeyListContainer" class="key-container"><select id="foundKeyList" size="10" class="key-list"></select></div>';
    $('.keyListCanvas').empty();
    $('.keyListCanvas').append(listElement);

    keyList = $('#foundKeyList');
    keyContainer = $('#foundKeyListContainer');

    var keyInput = placeholderTranslationContainer.find("input[id$='translationKey']");
    var loadingSpinner = $(placeholderTranslationContainer.find('.loading'));

    var ajaxUrl = '/cms-gui/create-glossary/search?{0}={1}';
    if (searchType == searchTypeGlossaryKey) {
        ajaxUrl = ajaxUrl.formatString('key', keyInput.val());
    } else if(searchType == searchTypeFullText) {
        ajaxUrl = ajaxUrl.formatString('value', keyInput.val());
    }

    keyList.find('option').remove();
    loadingSpinner.show();

    xhr = $.ajax({
        type: 'GET',
        url: ajaxUrl,
        success: function(data) {
            loadingSpinner.hide();

            $.each(data, function (i, item) {
                keyList.append($('<option>', {
                    value: i,
                    text : item.key
                }));

                keyContainer.css({ width: keyInput.width() + GLOSSARY_SELECT_MARGIN_WIDTH });
                keyContainer.show();
            });

            keyList.css({ height :  data.length * 17 });
            keyList.on('change', function() {
                keyInput.val(data[this.value].key);
                var translations = data[this.value].translations;
                $.each(translations, function (idLocale, translation) {
                    var translationElements = placeholderTranslationContainer.find("textarea[id$='translation']");
                    $.each(translationElements, function (translationElementIndex, translationElement) {
                         var translationIdLocale = $(translationElement).parent().parent().find("input[id$='fkLocale']").val();
                         if (translationIdLocale == idLocale) {
                             $(translationElement).summernote('reset');
                             $(translationElement).summernote('pasteHTML', translation.value);
                         }
                    });
                });
            });

            keyList.on('keydown', function(e) {
                var key = e.keyCode;
                if (key == 13 || key == 9) {
                    keyList.blur();
                    return false;
                }
            });

            keyList.on('blur', function() {
                keyInput.val(data[this.value].key);
                keyContainer.hide();
                keyInput.focus();
                return false;
            });
        }
    });
}

var addKeySearchEvent = function(autocompleteElement)
{
    var autocompleteElement = $(autocompleteElement);
    var placeholderTranslationContainer = $(autocompleteElement.parent().parent());

    var searchOption = $(placeholderTranslationContainer.find("select[id$='searchOption']"));

    autocompleteElement.on('input', function() {
        if ($(this).val().length > 3) {
            delay(function(){
                if(xhr && xhr.readystate != 4){
                    xhr.abort();
                }
                if (searchOption.val() == 2 || searchOption.val() == 3) {
                    showAutoComplete(placeholderTranslationContainer, searchOption.val());
                }
            }, 500);
        }
    });

    if (searchOption.val() == 0 && autocompleteElement.val() == '') {
        autocompleteElement.attr('disabled', 'disabled');
    } else {
        searchOption.val(1);
    }

    searchOption.on('change', function() {
        if (this.value == 0) {
            autocompleteElement.attr('disabled','disabled');
        } else {
            autocompleteElement.removeAttr('disabled');
        }
    });

    autocompleteElement.on('keyup', function(e) {
        var key = e.keyCode;

        if (key == 40) {
            keyList.first().focus();
            keyList.val(0).change();
        }
    });
};

var delay = (function(){
    var timer = 0;
    return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
    };
})();

$(document).ready(function(){
    $("input[id$='translationKey']").each(function(index, element){
        addKeySearchEvent(element);
    });
});

$(document).on('click', function(e) {
    if (keyContainer !== null && !$(e.target).is('option')) {
        keyContainer.hide();
    }
    if (itemContainer !== null && !$(e.target).is('option')) {
        itemContainer.hide();
    }
});
