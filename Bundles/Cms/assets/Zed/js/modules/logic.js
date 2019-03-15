/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var summernote = require('ZedGuiEditorConfiguration');

const GLOSSARY_SELECT_MARGIN_TOP = 159;
const GLOSSARY_SELECT_MARGIN_LEFT = 230;
const GLOSSARY_SELECT_MARGIN_WIDTH = 25;

var xhr = null;
var keyList = null;
var keyContainer = null;
var itemList = null;
var itemContainer = null;
var successResponseCount = 0;

String.prototype.formatString = function(){
    var args = arguments;
    return this.replace(/{(\d+)}/g, function(match, index){
        return args[index];
    });
};

function postForm( $form, id, successCallback ){
    var values = {};
    $.each($form.serializeArray(), function(i, field) {
        values[field.name] = field.value;
    });

    if(!(/\S/.test(values['form[glossary_key]'])) && values['form[search_option]'] != 0){
        id++;
        $('.error_' + id).text('The glossary key cannot be empty!');
        $('.waiting_' + id).text('');
        return;
    }

    $.ajax({
        type: $form.attr( 'method' ),
        url: '?id-page={0}&id-form={1}'.formatString($('#idPage').val(), id),
        data: values,
        success: function(data) {
            successCallback(data);
        }
    });
}

var ajaxifySubmmit = function(formId) {
    $('.form_class_' + formId).submit( function( e ){
        e.preventDefault();

        $('.waiting_' + formId).text('Waiting ...');
        $('.success_' + formId).text('');
        $('.error_' + formId).text('');

        postForm( $(this),  (formId - 1),
            function( response ){
                $('.waiting_' + formId).text('');
                if(response.success != 'false'){
                    var form = $('.form_class_' + formId);
                    var keyInput = form.find('#cms_glossary_glossary_key');
                    var keyType = form.find('#cms_glossary_search_option');

                    if (keyType.val() == 0) {
                        keyInput.removeAttr('disabled');
                        keyInput.val(response.glossaryKeyName);
                        keyType.val(1);
                    }
                    $('.success_' + formId).text('Successfully added.');
                    successResponseCount++;
                }else{
                    $('.error_' + formId).text(response.errorMessages);
                }
        });

        return false;
    });
};

function showAutoComplete(formId, searchType) {
    var searchTypeGlossaryKey = 2;
    var searchTypeFullText = 3;
    var listElement = '<div id="foundKeyListContainer" class="key-container"><select id="foundKeyList" size="10" class="key-list"></select></div>';
    $('.keyListCanvas').empty();
    $('.keyListCanvas').append(listElement);

    keyList = $('#foundKeyList');
    keyContainer = $('#foundKeyListContainer');

    var form = $('.form_class_' + formId);

    var keyInput = form.find('#cms_glossary_glossary_key');
    var keyTranslation = form.find('#cms_glossary_translation');
    var keyFkLocale = form.find('#cms_glossary_fk_locale');

    var ajaxUrl = 'glossary/search?{0}={1}&localeId={2}';

    if (searchType == searchTypeGlossaryKey) {
        ajaxUrl = ajaxUrl.formatString('key', keyInput.val(), keyFkLocale.val());
    } else if(searchType == searchTypeFullText) {
        ajaxUrl = ajaxUrl.formatString('value', keyInput.val(), keyFkLocale.val());
    } else {
        ajaxUrl = '';
    }

    keyList.find('option').remove();
    $('.loading-' + formId).show();

    xhr = $.ajax({
        type: 'GET',
        url: ajaxUrl,
        success: function(data) {
            $('.loading-' + formId).hide();

            $.each(data, function (i, item) {
                keyList.append($('<option>', {
                    value: i,
                    text : item.key
                }));

                keyContainer.css({ top: keyInput.offset().top - GLOSSARY_SELECT_MARGIN_TOP });
                keyContainer.css({ left: keyInput.offset().left - GLOSSARY_SELECT_MARGIN_LEFT });
                keyContainer.css({ width: keyInput.width() + GLOSSARY_SELECT_MARGIN_WIDTH });
                keyContainer.show();
            });

            keyList.css({ height :  data.length * 17 });
            keyList.on('change', function() {
                var keyContent = data[this.value].value;
                keyTranslation.val(keyContent);

                keyInput.val(data[this.value].key);
                $(keyInput.closest('.row').find('#cms_glossary_translation')).summernote('destroy');
                $(keyInput.closest('.row').find('#cms_glossary_translation')).summernote(summernote.getConfig(keyContent));
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

var addKeySearchEvent = function(formId) {
    var form = $('.form_class_' + formId);
    var keyInput = form.find('#cms_glossary_glossary_key');
    var keyType = form.find('#cms_glossary_search_option');

    keyInput.on('input', function() {
        if($(this).val().length > 3){
            delay(function(){
                if(xhr && xhr.readystate != 4){
                    xhr.abort();
                }
                if (keyType.val() == 2 || keyType.val() == 3) {
                    showAutoComplete(formId, keyType.val());
                }
            }, 500 );
        }
    });

    if (keyType.val() == 0 && keyInput.val() == '') {
        keyInput.attr('disabled','disabled');
    } else {
        keyType.val(1);
    }

    keyType.on('change', function() {
        if (this.value == 0) {
            keyInput.attr('disabled','disabled');
        } else {
            keyInput.removeAttr('disabled');
        }
    });

    keyInput.on('keyup', function(e) {
        var key = e.keyCode;

        if (key == 40) {
            keyList.first().focus();
            keyList.val(0).change();
        }
    });
};

function showBlockAutoComplete(elementId, type) {
    var listElement = '<div id="foundItemListContainer" class="key-container"><select id="foundItemList" size="10" class="key-list"></select></div>'
    $('.itemListCanvas').empty();
    $('.itemListCanvas').append(listElement);

    itemList = $('#foundItemList');
    itemContainer = $('#foundItemListContainer');

    var elementInput = $(elementId);

    var blockValue = $('#cms_block_value');
    var ajaxUrl = (type == 'category') ? '/cms/block/search-category?term={0}' : '/cms/block/search-product?term={0}';

    itemList.find('option').remove();

    var loadingBlock = $('.block-loading');
    loadingBlock.css({ top: elementInput.offset().top - 108, left: elementInput.offset().left - 235, position : 'absolute'});
    loadingBlock.show();

    xhr = $.ajax({
        type: 'GET',
        url: ajaxUrl.formatString(elementInput.val()),
        success: function(data) {
            $('.block-loading').hide();

            $.each(data, function (i, item) {
                itemList.append($('<option>', {
                    value: i,
                    text: '{0} -> {1}'.formatString(item.name, item.url)
                }));

                itemContainer.css({ top: elementInput.offset().top - 108 });
                itemContainer.css({ left: elementInput.offset().left - 235 });
                itemContainer.css({ width: elementInput.width() + 25 });
                itemContainer.show();
            });

            itemList.css({ height :  data.length * 17 });

            itemList.on('keydown', function(e) {
                var key = e.keyCode;
                if (key == 13 || key == 9) {
                    itemList.blur();
                    return false;
                }
            });

            itemList.on('blur', function() {
                elementInput.val(data[this.value].name);
                blockValue.val(data[this.value].id);
                itemContainer.hide();
                elementInput.focus();
                return false;
            });
        }
    });
}

var addAutoCompleteSearchEvent = function(elementId) {
    var elementInput = $(elementId);
    var elementType = $('#cms_block_type');

    elementInput.attr( 'autocomplete', 'off' );

    elementInput.on('input', function() {
        if($(this).val().length > 3){
            delay(function(){
                if(xhr && xhr.readystate != 4){
                    xhr.abort();
                }
                if (elementType.val() != 0) {
                    showBlockAutoComplete(elementId, elementType.val());
                }
            }, 500 );
        }
    });

    elementInput.on('keyup', function(e) {
        var key = e.keyCode;

        if (key == 40) {
            itemList.first().focus();
            itemList.val(0).change();
        }
    });

    if (elementType.val() == 'static') {
        $('#cms_block_selectValue').attr('disabled','disabled');
        $('#cms_block_value').val(0);
    }

    elementType.on('change', function() {
        if (this.value == 'static') {
            $('#cms_block_selectValue').attr('disabled','disabled');
            $('#cms_block_value').attr('value',0);
        } else {
            $('#cms_block_selectValue').removeAttr('disabled');
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
    addAutoCompleteSearchEvent('#cms_block_selectValue');

    $('.cms_form').each(function(index, item){
        var formId = $(item).attr('data-index');
        ajaxifySubmmit(formId);
        addKeySearchEvent(formId);
    });

    $('#saveAll').on('click',function(){
        $('.save-all-message').text('');
        $('.save-all-loading').show();
        var formCount = 0;
        successResponseCount = 0;
        $('.cms_form').each(function(index, item){
            var formId = $(item).attr('data-index');
            $('.form_class_' + formId).submit();
            formCount++;
        });

        $(document).ajaxStop(function() {
            $('.save-all-loading').hide();
            $('.save-all-message').text(successResponseCount + ' of ' + formCount + ' is done.');
            if (formCount == successResponseCount) {
                $('.save-all-message').css('color','green');
            } else if (formCount > successResponseCount && successResponseCount != 0) {
                $('.save-all-message').css('color','orange');
            } else {
                $('.save-all-message').css('color','red');
            }
            $(this).unbind("ajaxStop");
        });
    });

    $('.gui-table-data').on('draw.dt', function () {
        const $toggleWrap = $(this).find('.dropdown');
        let $toggleDropdown;

        $toggleWrap.on('show.bs.dropdown', function () {
            const $button = $(this).find('.dropdown-toggle'),
                buttonLeftOffset = $button.offset().left,
                buttonTopOffset = $button.offset().top,
                buttonHeight = $button.height();

            $toggleDropdown = $(this).find('.dropdown-menu');

            $('body').append($toggleDropdown.css({
                position: 'absolute',
                left: buttonLeftOffset + 'px',
                top: buttonTopOffset + buttonHeight + 5 + 'px',
                display: 'block',
                zIndex: '10000'
            }).detach());
        });

        $toggleWrap.on('hidden.bs.dropdown', function () {
            $(this).append($toggleDropdown.removeAttr('style').detach());
        });
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
