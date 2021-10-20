<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductSearch\PageObject;

class SearchPreferencesPage
{
    /**
     * @var string
     */
    public const URL_LIST = '/product-search/search-preferences';

    /**
     * @var string
     */
    public const URL_CREATE = '/product-search/search-preferences/create';

    /**
     * @var string
     */
    public const URL_EDIT = '/product-search/search-preferences/edit?id=';

    /**
     * @var string
     */
    public const SELECTOR_SEARCH_PREFERENCES_LIST = '.dataTables_wrapper';

    /**
     * @var string
     */
    public const SELECTOR_INPUT_SEARCH_PREFERENCES_KEY = '#searchPreferences_key';

    /**
     * @var string
     */
    public const SELECTOR_INPUT_SEARCH_PREFERENCES_FULL_TEXT = '#searchPreferences_fullText';

    /**
     * @var string
     */
    public const SELECTOR_INPUT_SEARCH_PREFERENCES_SUGGESTION_TERMS = '#searchPreferences_suggestionTerms';

    /**
     * @var string
     */
    public const SELECTOR_INPUT_SEARCH_PREFERENCES_COMPLETION_TERMS = '#searchPreferences_completionTerms';

    /**
     * @var string
     */
    public const SELECTOR_SEARCH_PREFERENCES_SUBMIT = '#searchPreferences_submit';

    /**
     * @var string
     */
    public const SELECTOR_FIRST_ROW_UPDATE = '.dataTable tbody tr:first-child td:last-child .btn-edit';

    /**
     * @var string
     */
    public const SELECTOR_INPUT_SEARCH_PREFERENCES_FULL_TEXT_BOOSTED = '#searchPreferences_fullTextBoosted';

    /**
     * @var string
     */
    public const SELECTOR_ALERT_SUCCESS = '.alert-success';

    /**
     * @var string
     */
    public const SELECTOR_TABLE_FIRST_CELL = '.dataTable tbody tr:first-child td:first-child';

    /**
     * @var string
     */
    public const SELECTOR_FIRST_ROW_DELETE = '.dataTable tbody tr:first-child td:last-child .btn-danger';

    /**
     * @var string
     */
    public const SELECTOR_TABLE_SEARCH = '.dataTables_filter input[type="search"]';
}
