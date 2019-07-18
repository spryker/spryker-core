<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductSearch\PageObject;

class FilterPreferencesPage
{
    public const URL_LIST = '/product-search/filter-preferences';
    public const URL_CREATE = '/product-search/filter-preferences/create';
    public const URL_VIEW = '/product-search/filter-preferences/view?id=';
    public const URL_EDIT = '/product-search/filter-preferences/edit?id=';

    public const SELECTOR_FILTER_LIST = '.dataTables_wrapper';
    public const SELECTOR_SAVE_FILTER_ORDER = '#save-filter-order';
    public const SELECTOR_ORDER_SAVE_ALERT = '.sweet-alert > h2';
    public const SELECTOR_SYNC_FILTERS = '#syncFilters';
    public const SELECTOR_ATTRIBUTE_FORM_SUBMIT = '#attributeForm_submit';
    public const SELECTOR_INPUT_FILTER_TYPE = '#attributeForm_filter_type';
    public const SELECTOR_INPUT_ATTRIBUTE_NAME_TRANSLATION = '.name-translation';
    public const SELECTOR_ATTRIBUTE_KEY = '#attributeForm_key';
    public const SELECTOR_COPY_ATTRIBUTE_NAME_TRANSLATION_BUTTON = '.name-translation ~ span > button';
    public const SELECTOR_BUTTON_EDIT = '.title-action > .btn-edit';
    public const SELECTOR_BUTTON_DELETE = '.title-action > .btn-remove';
}
