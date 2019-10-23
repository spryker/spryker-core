/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var BlocksChoice = function (options) {
    var _self = this;
    this.blocksChoiceFormSelector = {};
    this.blocksChoiceForm = {};
    this.blocksTable = {};

    $.extend(this, options);

    this.init = function () {
        _self.blocksChoiceForm = $(_self.blocksChoiceFormSelector);

        _self.blocksChoiceForm.on('submit', _self.addBlock);
    };

    this.addBlock = function (event) {
        event.preventDefault();

        var selectedBlock = _self.blocksChoiceForm.find('select option:selected');

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
    };
};

module.exports = BlocksChoice;
