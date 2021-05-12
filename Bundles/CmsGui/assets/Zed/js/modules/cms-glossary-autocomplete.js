/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var summernote = require('ZedGuiEditorConfiguration');

const GLOSSARY_SELECT_MARGIN_WIDTH = 25;

function CmsGlossaryAutocomplete(options) {
    var self = this;

    this.xhr = null;
    this.keyList = null;
    this.keyContainer = null;
    this.itemContainer = null;

    this.autocompleteElement = options.autocompleteElement;
    this.placeholderTranslationContainer = $(this.autocompleteElement.parent().parent());
    this.autocompleteUrl = '/cms-gui/create-glossary/search?{0}={1}';
    this.loadingSpinner = $(this.placeholderTranslationContainer.find('.loading'));
    this.keyInput = this.placeholderTranslationContainer.find("input[id$='translationKey']");
    this.listElement =
        '<div id="foundKeyListContainer" class="key-container"><select id="foundKeyList" size="10" class="key-list"></select></div>';

    this.addKeySearchEvent(this.autocompleteElement);

    $(document).on('click', function (e) {
        if (self.keyContainer !== null && !$(e.target).is('option')) {
            self.keyContainer.hide();
        }
        if (self.itemContainer !== null && !$(e.target).is('option')) {
            self.itemContainer.hide();
        }
    });
}

CmsGlossaryAutocomplete.prototype.showAutoComplete = function (placeholderTranslationContainer, searchType) {
    var keyListCanvas = $(this.placeholderTranslationContainer.find('.keyListCanvas'));
    keyListCanvas.empty();
    keyListCanvas.append(this.listElement);

    this.keyList = $(keyListCanvas.find('#foundKeyList'));
    this.keyContainer = $(keyListCanvas.find('#foundKeyListContainer'));

    this.keyList.find('option').remove();
    this.loadingSpinner.show();

    self.xhr = $.ajax({
        type: 'GET',
        url: this.buildAutocompleteUrl(searchType, this.keyInput.val()),
        context: this,
        success: this.handleAjaxResponse,
    });
};

CmsGlossaryAutocomplete.prototype.handleAjaxResponse = function (response) {
    var self = this;
    this.loadingSpinner.hide();

    $.each(response, function (i, item) {
        self.keyList.append(
            $('<option>', {
                value: i,
                text: item.key,
            }),
        );

        self.keyContainer.css({ width: self.keyInput.width() + GLOSSARY_SELECT_MARGIN_WIDTH });
        self.keyContainer.show();
    });

    self.keyList.css({ height: response.length * 20 });
    self.keyList.on('change', function () {
        self.setSelectedTranslationValueFromResponse(response, this);
    });

    self.keyList.on('keydown', function (e) {
        var key = e.keyCode;
        if (key == 13 || key == 9) {
            self.keyList.blur();
            return false;
        }
    });

    self.keyList.on('blur', function () {
        self.keyInput.val(response[this.value].key);
        self.keyContainer.hide();
        self.keyInput.focus();
        return false;
    });
};

CmsGlossaryAutocomplete.prototype.setSelectedTranslationValueFromResponse = function (response, selectChange) {
    var self = this;
    this.keyInput.val(response[selectChange.value].key);
    var translations = response[selectChange.value].translations;
    $.each(translations, function (idLocale, translation) {
        var translationElements = self.placeholderTranslationContainer.find("textarea[id$='translation']");
        $.each(translationElements, function (translationElementIndex, translationElement) {
            var translationIdLocale = $(translationElement).parent().parent().find("input[id$='fkLocale']").val();
            if (translationIdLocale == idLocale) {
                $(translationElement).summernote('reset');
                $(translationElement).summernote('pasteHTML', translation.value);
            }
        });
    });
};

CmsGlossaryAutocomplete.prototype.buildAutocompleteUrl = function (searchType, value) {
    var searchTypeGlossaryKey = 2;
    var searchTypeFullText = 3;

    if (searchType == searchTypeGlossaryKey) {
        return this.autocompleteUrl.formatString('key', value);
    } else if (searchType == searchTypeFullText) {
        return this.autocompleteUrl.formatString('value', value);
    }
};

CmsGlossaryAutocomplete.prototype.addKeySearchEvent = function (autocompleteElement) {
    var searchOption = $(this.placeholderTranslationContainer.find("select[id$='searchOption']"));

    var delay = (function () {
        var timer = 0;
        return function (callback, ms) {
            clearTimeout(timer);
            timer = setTimeout(callback, ms);
        };
    })();

    var self = this;
    autocompleteElement.on('input', function () {
        if ($(this).val().length > 3) {
            delay(function () {
                if (self.xhr && self.xhr.readystate != 4) {
                    self.xhr.abort();
                }
                if (searchOption.val() == 2 || searchOption.val() == 3) {
                    self.showAutoComplete(self.placeholderTranslationContainer, searchOption.val());
                }
            }, 500);
        }
    });

    if (searchOption.val() == 0 && autocompleteElement.val() == '') {
        autocompleteElement.attr('readonly', 'readonly');
    } else {
        searchOption.val(1);
    }

    searchOption.on('change', function () {
        if (this.value == 0) {
            autocompleteElement.attr('readonly', 'readonly');
        } else {
            autocompleteElement.removeAttr('readonly');
        }
    });

    autocompleteElement.on('keyup', function (e) {
        var key = e.keyCode;

        if (key == 40) {
            self.keyList.first().focus();
            self.keyList.val(0).change();
        }
    });
};

String.prototype.formatString = function () {
    var args = arguments;
    return this.replace(/{(\d+)}/g, function (match, index) {
        return args[index];
    });
};

module.exports = CmsGlossaryAutocomplete;
