<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductSearch\PageObject;

class SearchPreferencesPage
{
    const URL_LIST = '/product-search/search-preferences';
    const URL_CREATE = '/product-search/search-preferences/create';
    const URL_EDIT = '/product-search/search-preferences/edit?id=';

    const SELECTOR_SEARCH_PREFERENCES_LIST = '.dataTables_wrapper';
    const SELECTOR_INPUT_SEARCH_PREFERENCES_KEY = '#searchPreferences_key';
    const SELECTOR_INPUT_SEARCH_PREFERENCES_FULL_TEXT = '#searchPreferences_fullText';
    const SELECTOR_INPUT_SEARCH_PREFERENCES_SUGGESTION_TERMS = '#searchPreferences_suggestionTerms';
    const SELECTOR_INPUT_SEARCH_PREFERENCES_COMPLETION_TERMS = '#searchPreferences_completionTerms';
    const SELECTOR_SEARCH_PREFERENCES_SUBMIT = '#searchPreferences_submit';
    const SELECTOR_FIRST_ROW_UPDATE = '.dataTable tbody tr:first-child td:last-child .btn-edit';
    const SELECTOR_INPUT_SEARCH_PREFERENCES_FULL_TEXT_BOOSTED = '#searchPreferences_fullTextBoosted';
    const SELECTOR_ALERT_SUCCESS = '.alert-success';
    const SELECTOR_TABLE_FIRST_CELL = '.dataTable tbody tr:first-child td:first-child';
    const SELECTOR_FIRST_ROW_DELETE = '.dataTable tbody tr:first-child td:last-child .btn-danger';
    const SELECTOR_TABLE_SEARCH = '.dataTables_filter input[type="search"]';
}
