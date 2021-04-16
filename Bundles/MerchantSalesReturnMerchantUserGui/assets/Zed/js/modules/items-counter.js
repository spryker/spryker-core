/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

'use strict';

function ItemsCounter(options) {
    $.extend(this, options);

    this.$table = $(this.tableSelector);
    this.$allItems = this.$table.find(this.allItemsSelector);
    this.$item = this.$table.find(this.itemSelector);
    this.$counterWrapper = $(this.counterWrapperSelector);
    this.$counter = $(this.counterSelector);

    this.init = function () {
        this.mapEvents();
    };

    this.mapEvents = function () {
        var self = this;

        this.$item.on('change', function () {
            self.updateItemCounter();
        });

        this.$allItems.on('change', function () {
            setTimeout(function () {
                self.updateItemCounter();
            }, 0);
        });
    };

    this.updateItemCounter = function () {
        var checkedItems = this.$table.find(this.checkedItemSelector).length;

        if (checkedItems) {
            this.$counter.text(checkedItems);
            this.$counterWrapper.removeClass('hidden');

            return;
        }

        this.$counterWrapper.addClass('hidden');
    };

    this.init();
}

module.exports = ItemsCounter;
