/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

$(document).ready(function () {
    const storeContextItemSelector = '.store-context-item';
    const dataItemIndex = 'item-index';

    function indexContexts() {
        $(storeContextItemSelector).each(function (i, item) {
            $(item).data(dataItemIndex, i);
        });
    }

    function disableSelectedApplication() {
        const $selectedContexts = $(storeContextItemSelector)
            .find('.select-application')
            .map(function () {
                return $(this).val();
            })
            .get();

        $(storeContextItemSelector)
            .find('select')
            .each(function () {
                const $select = $(this);
                $select.find('option').each(function () {
                    const $option = $(this);
                    if ($selectedContexts.includes($option.val())) {
                        $option.prop('disabled', true);
                    } else {
                        $option.prop('disabled', false);
                    }
                });
            });
    }

    /**
     * @param event {Event}
     */
    function addStoreContextItem(event) {
        event.preventDefault();

        let maxIndex = 0;

        $(storeContextItemSelector).each(function (i, item) {
            const index = $(item).data(dataItemIndex);
            if (index > maxIndex) {
                maxIndex = index;
            }
        });

        const newContextItemIndex = maxIndex + 1;

        const prototypeTemplate = $(event.target).closest('[data-context-collection-prototype]');
        const newOptionFormHTML = prototypeTemplate
            .data('contextCollectionPrototype')
            .replace(/__store_context__/g, newContextItemIndex)
            .trim();

        $('.wrapper-context-items').append($(newOptionFormHTML));

        indexContexts();
    }

    /**
     * @param event {Event}
     */
    function deleteStoreContextItem(event) {
        event.preventDefault();
        $(event.target).closest('.store-context-item').remove();
    }

    /**
     * Register global event listeners
     */
    $('body')
        .on('click', '.add-store-context', addStoreContextItem)
        .on('click', '.remove-store-context', deleteStoreContextItem);

    /**
     * Initialize
     */
    indexContexts();
});
