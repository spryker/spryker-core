/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var TranslationCopyFields = require('ZedGuiModules/libs/translation-copy-fields');
require('./main');

function OptionValueFormHandler() {
    this.skuPrefix = 'OP_';

    this.optionValueList = $('#option-value-list');
    this.optionValueTranslationsElement = $('#option-value-translations');
    this.optionValueCount = parseInt(this.optionValueList.data('value-count'));
    this.valueTranslationCount = parseInt(this.optionValueTranslationsElement.attr('data-value-count'));
    this.newOptionFormHTML = this.optionValueList.data('prototype');
    this.translationFormHTML = this.optionValueTranslationsElement.data('prototype');
    this.valuesToBeRemovedField = $('#product_option_general_product_option_values_to_be_removed');
    this.translationCopyButton = new TranslationCopyFields();

    this.addProductOptionValueForm();

    if (!this.isOptionValueFormAlreadyAdded()) {
        $('#add-another-option').trigger('click');
    } else {
        this.initialiseExistingProductValueForms();
    }
}

OptionValueFormHandler.prototype.isOptionValueFormAlreadyAdded = function () {
    var isOptionValueFormAlreadyAdded = false;
    $('#option-value-list')
        .find("input[id$='value']")
        .each(function (index, element) {
            isOptionValueFormAlreadyAdded = true;
            return;
        });

    return isOptionValueFormAlreadyAdded;
};

OptionValueFormHandler.prototype.initialiseExistingProductValueForms = function () {
    var self = this;
    $('.form-product-option-row').each(function (index, element) {
        var productOptionValueFormSelector = new ProductOptionValueFormSelector($(element));

        self.addOptionFormActions(productOptionValueFormSelector);

        var nameLabelElement = productOptionValueFormSelector.getValueField().prev();
        var currentLabelText = nameLabelElement.text();

        var numRow = index + 1;
        nameLabelElement.text(numRow + '. ' + currentLabelText);

        var optionValueFormHash = productOptionValueFormSelector.getOptionHashField().val();
        var relatedTranslationElements = $('#option-value-translations').find(
            "input[value$='" + optionValueFormHash + "']",
        );

        relatedTranslationElements.each(function (index, element) {
            var productOptionValueTranslationFormSelector = new ProductOptionTranslationFormSelector(
                $(element).parent(),
            );
            var translationLabel = productOptionValueTranslationFormSelector.getTranslationLabel();
            translationLabel.text(numRow + '. ' + translationLabel.text());
        });
    });
};

OptionValueFormHandler.prototype.addProductOptionValueForm = function () {
    var self = this;

    $('#add-another-option').click(function (event) {
        event.preventDefault();

        self.optionValueCount++;

        var newOptionFormHTML = self.newOptionFormHTML.replace(/__name__/g, self.optionValueCount);
        var newOptionForm = $(jQuery.parseHTML(newOptionFormHTML)[1]);
        var optionValueFormId = newOptionForm.attr('id');

        var productOptionValueFormSelector = new ProductOptionValueFormSelector(newOptionForm);

        productOptionValueFormSelector.getOptionHashField().val(optionValueFormId);

        self.addOptionTranslations(optionValueFormId, productOptionValueFormSelector);
        self.addOptionFormActions(productOptionValueFormSelector);

        var valueLabelElement = productOptionValueFormSelector.getValueField().prev();
        var currentLabelText = valueLabelElement.text();

        valueLabelElement.text(self.optionValueCount + '. ' + currentLabelText);

        self.optionValueList.append(newOptionForm);
    });
};

OptionValueFormHandler.prototype.addOptionFormActions = function (productOptionValueFormSelector) {
    var optionValueFormHash = productOptionValueFormSelector.getOptionHashField().val();
    var relatedTranslationElements = $('#product_option_general').find("input[value$='" + optionValueFormHash + "']");

    var self = this;

    productOptionValueFormSelector.getRemoveButton().on('click', function (event) {
        relatedTranslationElements.each(function (index, element) {
            $(element).parent().remove();
        });

        var currentValuesToBeRemoved = self.valuesToBeRemovedField.val();
        currentValuesToBeRemoved += productOptionValueFormSelector.getIdProductOptionValueField().val() + ',';
        self.valuesToBeRemovedField.val(currentValuesToBeRemoved);

        productOptionValueFormSelector.getIdProductOptionValueField().parent().remove();
    });

    productOptionValueFormSelector.getValueField().on('keyup', function (event) {
        var nameElementValue = $(event.target).val();

        relatedTranslationElements.each(function (index, element) {
            var translationKeyInput = $(element).parent().find("input[id$='key']");
            translationKeyInput.val(nameElementValue);
        });

        var formattedValue = nameElementValue.toLowerCase().replace(/\s/g, '_');
        var valueWithPrefix = self.skuPrefix + formattedValue;

        productOptionValueFormSelector.getSkuField().val(valueWithPrefix);
    });
};

OptionValueFormHandler.prototype.addOptionTranslations = function addOptionTranslations(
    idOfRelatedOption,
    productOptionValueFormSelector,
) {
    var translationPanels = this.optionValueTranslationsElement.find('.ibox-content');
    var self = this;

    translationPanels.each(function (index, element) {
        self.valueTranslationCount++;

        var translationFormHTML = self.translationFormHTML.replace(/__name__/g, self.valueTranslationCount);
        var newOptionValueTranslationForm = $(jQuery.parseHTML(translationFormHTML));
        $(element).append(newOptionValueTranslationForm);

        var productOptionValueTranslationFormSelector = new ProductOptionTranslationFormSelector(
            newOptionValueTranslationForm,
        );

        productOptionValueTranslationFormSelector
            .getLocaleCodeField()
            .val($(element).parent().parent().data('locale-code'));

        productOptionValueTranslationFormSelector.getNameField().attr({ 'data-translation-key': idOfRelatedOption });

        productOptionValueTranslationFormSelector
            .getKeyField()
            .val(productOptionValueFormSelector.getValueField().val());

        productOptionValueTranslationFormSelector.getRelatedOptionHashKeyField().val(idOfRelatedOption);

        self.translationCopyButton.addCopyButtons();

        var nameLabelElement = productOptionValueTranslationFormSelector.getTranslationLabel();
        var currentLabelText = nameLabelElement.text();

        nameLabelElement.text(self.optionValueCount + '. ' + currentLabelText);
    });
};

function ProductOptionValueFormSelector(form) {
    this.form = form;
}

ProductOptionValueFormSelector.prototype.getValueField = function () {
    return this.form.find("input[id$='value']");
};

ProductOptionValueFormSelector.prototype.getOptionHashField = function () {
    return this.form.find("input[id$='optionHash']");
};

ProductOptionValueFormSelector.prototype.getOptionHashField = function () {
    return this.form.find("input[id$='optionHash']");
};

ProductOptionValueFormSelector.prototype.getIdProductOptionValueField = function () {
    return this.form.find("input[id$='idProductOptionValue']");
};

ProductOptionValueFormSelector.prototype.getSkuField = function () {
    return this.form.find("input[id$='sku']");
};

ProductOptionValueFormSelector.prototype.getRemoveButton = function () {
    return this.form.find('.btn-remove');
};

function ProductOptionTranslationFormSelector(form) {
    this.form = form;
}

ProductOptionTranslationFormSelector.prototype.getLocaleCodeField = function () {
    return this.form.find("input[id$='localeCode']");
};

ProductOptionTranslationFormSelector.prototype.getKeyField = function () {
    return this.form.find("input[id$='key']");
};

ProductOptionTranslationFormSelector.prototype.getRelatedOptionHashKeyField = function () {
    return this.form.find("input[id$='relatedOptionHash']");
};

ProductOptionTranslationFormSelector.prototype.getTranslationLabel = function () {
    return this.getNameField().parent().prev();
};

ProductOptionTranslationFormSelector.prototype.getNameField = function () {
    return this.form.find("input[id$='name']");
};

module.exports = OptionValueFormHandler;
