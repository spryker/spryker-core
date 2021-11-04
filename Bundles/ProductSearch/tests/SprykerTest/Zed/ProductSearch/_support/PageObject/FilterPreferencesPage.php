<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductSearch\PageObject;

class FilterPreferencesPage
{
    /**
     * @var string
     */
    public const URL_LIST = '/product-search/filter-preferences';

    /**
     * @var string
     */
    public const URL_CREATE = '/product-search/filter-preferences/create';

    /**
     * @var string
     */
    public const URL_VIEW = '/product-search/filter-preferences/view?id=';

    /**
     * @var string
     */
    public const URL_EDIT = '/product-search/filter-preferences/edit?id=';

    /**
     * @var string
     */
    public const SELECTOR_FILTER_LIST = '.dataTables_wrapper';

    /**
     * @var string
     */
    public const SELECTOR_SAVE_FILTER_ORDER = '#save-filter-order';

    /**
     * @var string
     */
    public const SELECTOR_ORDER_SAVE_ALERT = '.sweet-alert > h2';

    /**
     * @var string
     */
    public const SELECTOR_SYNC_FILTERS = '#syncFilters';

    /**
     * @var string
     */
    public const SELECTOR_ATTRIBUTE_FORM_SUBMIT = '#attributeForm_submit';

    /**
     * @var string
     */
    public const SELECTOR_INPUT_FILTER_TYPE = '#attributeForm_filter_type';

    /**
     * @var string
     */
    public const SELECTOR_INPUT_ATTRIBUTE_NAME_TRANSLATION = '.name-translation';

    /**
     * @var string
     */
    public const SELECTOR_ATTRIBUTE_KEY = '#attributeForm_key';

    /**
     * @var string
     */
    public const SELECTOR_COPY_ATTRIBUTE_NAME_TRANSLATION_BUTTON = '.name-translation ~ span > button';

    /**
     * @var string
     */
    public const SELECTOR_BUTTON_EDIT = '.title-action > .btn-edit';

    /**
     * @var string
     */
    public const SELECTOR_BUTTON_DELETE = '//form[@name="delete_filter_preferences_form"]/button';
}
