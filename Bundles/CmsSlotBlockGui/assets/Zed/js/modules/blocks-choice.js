/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var BlocksChoice = function (options) {
    var _self = this;
    this.blocksChoiceFormSelector = {};
    this.$blocksChoiceForm = {};
    this.$blocksChoiceDropDown = {};
    this.blocksTable = {};
    this.blocksChoiceAddSelector = '[type=button]';

    $.extend(this, options);

    this.init = function () {
        _self.$blocksChoiceForm = $(_self.blocksChoiceFormSelector);
        _self.$blocksChoiceDropDown = _self.$blocksChoiceForm.find('select');
        _self.initSelect();
        _self.$blocksChoiceDropDown.on('change', _self.selectBlockChoice);
        _self.$blocksChoiceForm.on('click', _self.blocksChoiceAddSelector, _self.addBlock);
    };

    this.initSelect = function () {
        _self.$blocksChoiceDropDown.select2();
    };

    this.resetSelect = function () {
        _self.$blocksChoiceDropDown.val('').trigger('change').select2();
    };

    this.selectBlockChoice = function () {
        var isSelected = _self.$blocksChoiceDropDown.val() !== '';
        $(_self.blocksChoiceAddSelector).toggleClass('btn-back', !isSelected)
            .toggleClass('btn-primary', isSelected);
    };

    this.addBlock = function (event) {
        event.preventDefault();
        var $selectedBlock = _self.$blocksChoiceDropDown.find('option:selected');
        if (!$selectedBlock.val()) {
            return;
        }

        var blockData = {
            blockId: $selectedBlock.val(),
            blockName: $selectedBlock.text(),
            validFrom: $selectedBlock.data('valid-from'),
            validTo: $selectedBlock.data('valid-to'),
            isActive: $selectedBlock.data('is-active'),
            stores: $selectedBlock.data('stores'),
        };

        _self.blocksTable.addRow(blockData);
        $selectedBlock.prop('disabled', true);
        _self.resetSelect();
    };
};

module.exports = BlocksChoice;
