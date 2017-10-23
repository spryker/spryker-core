<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductSearch\PageObject;

class FilterPreferencesPage
{
    const URL_LIST = '/product-search/filter-preferences';
    const URL_CREATE = '/product-search/filter-preferences/create';
    const URL_VIEW = '/product-search/filter-preferences/view?id=';
    const URL_EDIT = '/product-search/filter-preferences/edit?id=';

    const SELECTOR_FILTER_LIST = '.dataTables_wrapper';
    const SELECTOR_SAVE_FILTER_ORDER = '#save-filter-order';
    const SELECTOR_ORDER_SAVE_ALERT = '.sweet-alert > h2';
    const SELECTOR_SYNC_FILTERS = '#syncFilters';
    const SELECTOR_ATTRIBUTE_FORM_SUBMIT = '#attributeForm_submit';
    const SELECTOR_INPUT_FILTER_TYPE = '#attributeForm_filter_type';
    const SELECTOR_INPUT_ATTRIBUTE_NAME_TRANSLATION = '.name-translation';
    const SELECTOR_ATTRIBUTE_KEY = '#attributeForm_key';
    const SELECTOR_COPY_ATTRIBUTE_NAME_TRANSLATION_BUTTON = '.name-translation ~ span > button';
    const SELECTOR_BUTTON_EDIT = '.title-action > .btn-edit';
    const SELECTOR_BUTTON_DELETE = '.title-action > .btn-remove';
}
