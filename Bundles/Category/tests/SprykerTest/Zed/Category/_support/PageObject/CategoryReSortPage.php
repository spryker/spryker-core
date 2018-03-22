<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Category\PageObject;

class CategoryReSortPage
{
    const URL = '/category/re-sort?id-node=1';

    const SELECTOR_CATEGORY_LIST = '#category-list > .dd-list';
    const SELECTOR_FIRST_SUB_CATEGORY = self::SELECTOR_CATEGORY_LIST . ' > li.dd-item:first-child';
    const SELECTOR_SECOND_SUB_CATEGORY = self::SELECTOR_CATEGORY_LIST . ' > li.dd-item:nth-child(2)';
    const SELECTOR_LAST_SUB_CATEGORY = self::SELECTOR_CATEGORY_LIST . '> li.dd-item:last-child';
    const SELECTOR_FIRST_SUB_CATEGORY_NAME_CELL = self::SELECTOR_FIRST_SUB_CATEGORY . ' > .dd-handle';
    const SELECTOR_SECOND_SUB_CATEGORY_NAME_CELL = self::SELECTOR_SECOND_SUB_CATEGORY . '> .dd-handle';
    const SELECTOR_LAST_SUB_CATEGORY_NAME_CELL = self::SELECTOR_LAST_SUB_CATEGORY . ' > .dd-handle';
    const SELECTOR_SAVE_BUTTON = '#save-button';
    const SELECTOR_ALERT_BOX = '.sweet-alert';
}
