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
        _self.blocksTable.resetModifiedBlocks();
        _self.$blocksChoiceDropDown.select2({
            ajax: {
                url: _self.baseUrl,
                dataType: 'json',
                data: function (params) {
                    const paramsCollection = {};
                    paramsCollection[_self.paramTerm] = params.term;
                    paramsCollection[_self.paramPage] = params.page || 1;
                    paramsCollection[_self.paramIdCmsSlotTemplate] = _self.blocksTable.idCmsSlotTemplate;
                    paramsCollection[_self.paramIdCmsSlot] = _self.blocksTable.idCmsSlot;

                    return paramsCollection;
                },
                processResults: function (data) {
                    return {
                        ...data,
                        results: $.map(data.results, function (item) {
                            return {
                                ...item,
                                disabled: (item.disabled !== _self.blocksTable.isBlockModified(item.id))
                            }
                        }),
                    };
                },
                delay: 250,
                cache: true,
            },
            templateSelection: function (container) {
                $(container.element)
                    .data('is-active', container.isActive)
                    .data('valid-from', container.validFrom)
                    .data('valid-to', container.validFrom)
                    .data('stores', container.stores)

                return container.text;
            },
        });
    };

    this.resetSelect = function () {
        _self.$blocksChoiceDropDown.val('').trigger('change');
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
        _self.resetSelect();
    };
};

module.exports = BlocksChoice;
