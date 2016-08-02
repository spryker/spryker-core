/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');
require('../../sass/main.scss');

$(document).ready(function() {

    var valueList = $('#option-value-list');
    var valueCount = parseInt(valueList.data('value-count'));

    if (valueCount > 0) {
        $('.form-product-option-row').each(function(index, element) {

            var formProductOptionRow = $(element);
            addOptionFormActions(formProductOptionRow);

            var formProductOptionValueElement =  formProductOptionRow.find("input[id$='value']");

            var nameLabelElement = formProductOptionValueElement.prev();
            var currentLabelText = nameLabelElement.text();

            var numRow = index + 1;
            nameLabelElement.text(numRow + '. ' + currentLabelText);

            var optionValueFormHash = formProductOptionRow.find("input[id$='optionHash']").val();
            var relatedTranslationElements = $('#option-value-translations').find("input[value$='"+optionValueFormHash+"']");

            relatedTranslationElements.each(function(index, element) {
                var translationFormRow = $(element).parent();
                var translationLabel = translationFormRow.find("input[id$='name']").prev();

                translationLabel.text(numRow + '. ' + translationLabel.text());
            })
        });
    }

    $('#add-another-option').click(function(event) {
        event.preventDefault();

        valueCount++;

        var newOptionFormHTML = valueList.data('prototype');

        newOptionFormHTML = newOptionFormHTML.replace(/__name__/g, valueCount);
        var newOptionForm = $(jQuery.parseHTML(newOptionFormHTML)[1]);
        var optionValueFormId = newOptionForm.attr('id');

        var valueElement = newOptionForm.find("input[id$='value']");

        var formHashElement = newOptionForm.find("input[id$='optionHash']");
        formHashElement.val(optionValueFormId);

        addOptionTranslations(optionValueFormId, valueCount, valueElement);
        addOptionFormActions(newOptionForm);

        var valueLabelElement = valueElement.prev();
        var currentLabelText = valueLabelElement.text();

        valueLabelElement.text(valueCount + '. ' + currentLabelText);

        valueList.append(newOptionForm);

        newOptionForm.find("input[id$='sku']").removeAttr('readonly');
        newOptionForm.find("input[id$='value']").removeAttr('readonly');

    });

    function addOptionFormActions(newOptionForm) {

        var optionValueFormHash = newOptionForm.find("input[id$='optionHash']").val();
        var relatedTranslationElements = $('#product_option_general').find("input[value$='"+optionValueFormHash+"']");

        newOptionForm.find('.btn-remove').on('click', function(event) {

            relatedTranslationElements.each(function(index, element) {
                $(element).parent().remove()
            });

            $(event.target).parent().parent().remove();
        });

        var skuElement = newOptionForm.find("input[id$='sku']");
        var valueElement = newOptionForm.find("input[id$='value']");

        valueElement.on('keyup', function(event) {

            var nameElementValue = $(event.target).val();

            relatedTranslationElements.each(function(index, element) {
                var translationKeyInput = $(element).parent().find("input[id$='key']");
                translationKeyInput.val(nameElementValue);
            });

            var formattedValue = nameElementValue.toLowerCase().replace(/\s/g, '_');
            var valueWithPrefix = 'OP_' + formattedValue;

            skuElement.val(valueWithPrefix);

        });
    }

    function addOptionTranslations(idOfRelatedOption, valueCount, valueElement) {

        var optionValueTranslationsElement = $('#option-value-translations');
        var valueTranslationCount = parseInt(optionValueTranslationsElement.attr('data-value-count'));
        var translationPanels = optionValueTranslationsElement.find('.panel-body');

        translationPanels.each(function(index, element) {

            valueTranslationCount++;

            var translationFormHTML = optionValueTranslationsElement.data('prototype');
            translationFormHTML = translationFormHTML.replace(/__name__/g, valueTranslationCount);

            var newOptionValueTranslationForm = $(jQuery.parseHTML(translationFormHTML));

            var localeCode = $(element).data('locale-code');
            var localeCodeInput = newOptionValueTranslationForm.find("input[id$='localeCode']");
            localeCodeInput.val(localeCode);

            var translationKeyInput = newOptionValueTranslationForm.find("input[id$='key']");
            translationKeyInput.val(valueElement.val());

            var relatedOptionHash = newOptionValueTranslationForm.find("input[id$='relatedOptionHash']");
            $(relatedOptionHash).val(idOfRelatedOption);

            var nameLabelElement = newOptionValueTranslationForm.find("input[id$='name']").prev();
            var currentLabelText = nameLabelElement.text();

            nameLabelElement.text(valueCount + '. ' + currentLabelText);

            $(element).append(newOptionValueTranslationForm);
        });

        optionValueTranslationsElement.attr('data-value-count', valueTranslationCount);

    };

    $('#create-product-option-button').on('click', function(event) {
        event.preventDefault();

        $('#product_option_general').submit();
    });

    if ($('#create-product-option').length) {
        $('#add-another-option').trigger('click');
    }
});
