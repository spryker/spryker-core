/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var SlotBlocksForm = function (options) {
    var _self = this;
    this.formWrapper = {};
    this.form = {};
    this.saveButton = {};
    this.formItemsCount = 0;
    this.prototype = '';
    this.cmsSlotBlocksSelector = '';

    $.extend(this, options);

    this.init = function () {
        _self.formWrapper = $(_self.cmsSlotBlocksSelector);
        _self.form = _self.formWrapper.find('[name=slot_blocks]');
        _self.saveButton = _self.form.find('input[type=submit]');
        _self.formItemsCount = parseInt(_self.formWrapper.data('slot-block-item-count'));
        _self.prototype = _self.formWrapper.data('slot-block-item-form-prototype');

        _self.form.on('submit', _self.save);
    };

    this.rebuildForm = function (idCmsSlotTemplate, idCmsSlot, tablaData) {
        _self.form.find('[id*=slot_blocks_cmsSlotBlocks]').remove();

        var inputs = '';
        $(tablaData).each(function (index, item) {
            _self.formItemsCount++;

            var prototype = _self.prototype;
            var formItem = prototype.replace(/__name__/g,  _self.formItemsCount);
            formItem = $($.parseHTML(formItem));

            formItem.find('input[name*=\\[idSlotTemplate\\]]').val(idCmsSlotTemplate);
            formItem.find('input[name*=\\[idSlot\\]]').val(idCmsSlot);
            formItem.find('input[name*=\\[idCmsBlock\\]]').val(item[0]);
            formItem.find('input[name*=\\[position\\]]').val(_self.formItemsCount);

            inputs += formItem[0].outerHTML;
        });

        _self.formItemsCount = 0;
        _self.form.append(inputs);
    };

    this.save = function (event) {
        event.preventDefault();

        var url = $(this).attr('action');
        var formSerialize = $(this).serialize();

        $.post(url, formSerialize).done(function() {
            window.sweetAlert({
                title: 'Success',
                html: true,
                type: 'success'
            });
        }).fail(function() {
            window.sweetAlert({
                title: 'Error',
                html: true,
                type: 'error'
            });
        }).always(function() {
            _self.saveButton.removeAttr('disabled');
            _self.saveButton.removeClass('disabled');
        });
    };
};

/**
 * Open public methods
 */
module.exports = SlotBlocksForm;