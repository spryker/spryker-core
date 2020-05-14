/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

function ItemsToggler(options) {
    $.extend(this, options);

    this.$table = $(this.tableSelector);
    this.$allItems = this.$table.find(this.allItemsSelector);
    this.$item = this.$table.find(this.itemSelector);

    this.init = function() {
        this.mapEvents();
    };

    this.mapEvents = function() {
        var self = this;

        this.$item.on('change', function() {
            self.toggleAllItemsCheckbox();
        });

        this.$allItems.on('change', function() {
            self.toggleItemCheckbox($(this));
        });
    };

    this.toggleAllItemsCheckbox = function() {
        var checkedItems = this.$table.find(this.checkedItemSelector).length;

        if (this.$item.length === checkedItems) {
            this.$allItems.prop('checked', true);

            return;
        }

        this.$allItems.prop('checked', false);
    };

    this.toggleItemCheckbox = function($item) {
        this.$item.prop('checked', $item.prop('checked'));
    };

    this.init();
}

module.exports = ItemsToggler;
