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
        _self.$blocksChoiceForm.on('click', _self.blocksChoiceAddSelector, _self.addBlock);
    };

    this.initSelect = function () {
        _self.$blocksChoiceDropDown.select2();
    };

    this.resetSelect = function () {
        _self.$blocksChoiceDropDown.select2().val(null);
    };

    this.addBlock = function (event) {
        event.preventDefault();
        var selectedBlock = _self.$blocksChoiceDropDown.find('option:selected');

        if (!selectedBlock.val()) {
            return;
        }

        var blockData = {
            blockId: selectedBlock.val(),
            blockName: selectedBlock.text(),
            validFrom: selectedBlock.data('valid-from'),
            validTo: selectedBlock.data('valid-to'),
            isActive: selectedBlock.data('is-active'),
            stores: selectedBlock.data('stores'),
        };

        _self.blocksTable.addRow(blockData);
        selectedBlock.attr("disabled", true);
        _self.resetSelect();
    };
};

module.exports = BlocksChoice;
