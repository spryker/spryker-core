<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\ProductSearch\Zed\SearchPreferences\Tester;

use Acceptance\ProductSearch\Zed\SearchPreferences\PageObject\SearchPreferencesPage;
use ProductSearch\ZedAcceptanceTester;

class SearchPreferencesTester extends ZedAcceptanceTester
{

    /**
     * @param string $attributeKey
     *
     * @return void
     */
    public function addAttributeToSearch($attributeKey)
    {
        $this->amOnPage(SearchPreferencesPage::URL_CREATE);

        $this->fillField(SearchPreferencesPage::SELECTOR_INPUT_SEARCH_PREFERENCES_KEY, $attributeKey);
        $this->selectOption(SearchPreferencesPage::SELECTOR_INPUT_SEARCH_PREFERENCES_FULL_TEXT, 'yes');
        $this->selectOption(SearchPreferencesPage::SELECTOR_INPUT_SEARCH_PREFERENCES_SUGGESTION_TERMS, 'yes');
        $this->selectOption(SearchPreferencesPage::SELECTOR_INPUT_SEARCH_PREFERENCES_COMPLETION_TERMS, 'yes');

        $this->click(SearchPreferencesPage::SELECTOR_SEARCH_PREFERENCES_SUBMIT);

        $this->canSeeCurrentUrlEquals(SearchPreferencesPage::URL_LIST);

        $this->canSee('Attribute to search was added successfully.', SearchPreferencesPage::SELECTOR_ALERT_SUCCESS);
    }

    /**
     * @param string $attributeKey
     *
     * @return void
     */
    public function updateAttributeToSearch($attributeKey)
    {
        $this->searchTableByAttributeKey($attributeKey);

        $this->click(SearchPreferencesPage::SELECTOR_FIRST_ROW_UPDATE);

        $this->canSeeCurrentUrlMatches('/' . preg_quote(SearchPreferencesPage::URL_EDIT, '/') . '(\d+)/');

        $this->selectOption(SearchPreferencesPage::SELECTOR_INPUT_SEARCH_PREFERENCES_FULL_TEXT, 'no');
        $this->selectOption(SearchPreferencesPage::SELECTOR_INPUT_SEARCH_PREFERENCES_FULL_TEXT_BOOSTED, 'yes');

        $this->click(SearchPreferencesPage::SELECTOR_SEARCH_PREFERENCES_SUBMIT);

        $this->canSeeCurrentUrlEquals(SearchPreferencesPage::URL_LIST);

        $this->canSee('Attribute to search was successfully updated.', SearchPreferencesPage::SELECTOR_ALERT_SUCCESS);
    }

    /**
     * @param string $attributeKey
     *
     * @return void
     */
    public function deactivateAttributeToSearch($attributeKey)
    {
        $this->searchTableByAttributeKey($attributeKey);

        $this->click(SearchPreferencesPage::SELECTOR_FIRST_ROW_DELETE);

        $this->canSeeCurrentUrlEquals(SearchPreferencesPage::URL_LIST);

        $this->canSee('Attribute to search was successfully deactivated.', SearchPreferencesPage::SELECTOR_ALERT_SUCCESS);
    }

    /**
     * @param string $attributeKey
     *
     * @return void
     */
    protected function searchTableByAttributeKey($attributeKey)
    {
        $this->amOnPage(SearchPreferencesPage::URL_LIST);
        $this->fillField(SearchPreferencesPage::SELECTOR_TABLE_SEARCH, $attributeKey);
        $this->wait(3);

        $this->canSee($attributeKey, SearchPreferencesPage::SELECTOR_TABLE_FIRST_CELL);
    }

}
