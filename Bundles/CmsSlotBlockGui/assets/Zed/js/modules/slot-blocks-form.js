/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var SlotBlocksForm = function (options) {
    var _self = this;
    this.$formWrapper = {};
    this.form = {};
    this.saveButton = {};
    this.formItemsCount = 0;
    this.formTemplate = '';
    this.cmsSlotBlocksSelector = '';
    this.isStateChanged = false;
    this.slotBlockFormItemClass = '';
    this.slotBlockFormItemIdPrefix = '';
    this.slotBlockFormWrapperId = '';

    $.extend(this, options);

    this.init = function () {
        _self.$formWrapper = $(_self.cmsSlotBlocksSelector);
        _self.form = _self.$formWrapper.find('[name=slot_blocks]');
        _self.saveButton = _self.form.find('input[type=submit]');
        _self.formItemsCount = parseInt(_self.$formWrapper.data('slot-block-item-count'));
        _self.formTemplate = _self.$formWrapper.data('slot-block-item-form-template');
        _self.form.on('submit', _self.save);
    };

    this.rebuildForm = function (idCmsSlotTemplate, idCmsSlot, tableData, isChanged) {
        if (isChanged && $(tableData).length < 1) {
            $(_self.slotBlockFormWrapperId).empty();
            _self.isStateChanged = isChanged;

            return;
        }

        _self.formItemsCount = $(_self.slotBlockFormItemClass).length;
        var prevCmsBlockId = 0;

        $(tableData).each(function (index, item) {
            var formItem = $(_self.slotBlockFormItemIdPrefix + item[0]);

            if (formItem.length < 1) {
                formItem = _self.createNewFormElement(idCmsSlotTemplate, idCmsSlot, item[0]);
            }

            formItem.find('input[name*=\\[position\\]]').val(index + 1);
            $(_self.slotBlockFormWrapperId).prepend(formItem);

            if (prevCmsBlockId > 0) {
                formItem.insertAfter(
                    $(_self.slotBlockFormItemIdPrefix + prevCmsBlockId)
                );
            }

            prevCmsBlockId = item[0];
        });

        $(_self.slotBlockFormItemIdPrefix + prevCmsBlockId).nextAll().remove();
        _self.initFormItems();

        _self.isStateChanged = isChanged;
    };

    this.save = function (event) {
        event.preventDefault();
        if (!_self.isStateChanged) {
            setTimeout(function() {
                _self.activateButton();
            }, 0);
            return;
        }
        var url = $(this).attr('action');
        var formSerialize = $(this).serialize();

        $.post(url, formSerialize).done(function(response) {
            window.sweetAlert({
                title: 'Success',
                html: true,
                type: 'success'
            });
            $(_self.slotBlockFormWrapperId).html(response);
            _self.initFormItems();
            $(document).trigger('savedBlocksForm');
            _self.isStateChanged = false;
        }).fail(function() {
            window.sweetAlert({
                title: 'Error',
                html: true,
                type: 'error'
            });
        }).always(function() {
            _self.activateButton();
        });
    };

    this.activateButton = function () {
        _self.saveButton.removeAttr('disabled');
        _self.saveButton.removeClass('disabled');
    };

    this.createNewFormElement = function (idCmsSlotTemplate, idCmsSlot, idCmsBlock) {
        var formTemplate = '<div class="js-cms-slot-block-form-item" ' +
            'id="js-cms-slot-block-form-item-' + idCmsBlock + '">' +
            _self.formTemplate +
            '</div>';
        var formItem = formTemplate.replace(/__name__/g,  _self.formItemsCount);
        formItem = $($.parseHTML(formItem));
        formItem.find('input[name*=\\[idSlotTemplate\\]]').val(idCmsSlotTemplate);
        formItem.find('input[name*=\\[idSlot\\]]').val(idCmsSlot);
        formItem.find('input[name*=\\[idCmsBlock\\]]').val(idCmsBlock);

        return formItem;
    };

    this.initFormItems = function () {
        _self.form.find('select').each(function(index, element) {
            var select2InitOptions = {};
            var selectElement = $(element);

            if (selectElement.data('autocomplete-url')) {
                select2InitOptions = {
                    ajax: {
                        url: selectElement.data('autocomplete-url'),
                        dataType: 'json',
                        delay: 500,
                        cache: true,
                    },
                    minimumInputLength: 3
                };
            }
            selectElement.select2(select2InitOptions);
            selectElement.on('change', _self.changeState);
            _self.form.find('input').on('change', _self.setStateChanged);
        });
    };

    this.setStateChanged = function () {
        return _self.isStateChanged = true;
    }
};

module.exports = SlotBlocksForm;
