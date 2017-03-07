<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\ProductSearch\Zed\SearchPreferences;

use Acceptance\ProductSearch\Zed\SearchPreferences\PageObject\SearchPreferencesPage;
use Acceptance\ProductSearch\Zed\SearchPreferences\Tester\SearchPreferencesTester;

/**
 * @group Acceptance
 * @group ProductSearch
 * @group Zed
 * @group SearchPreferences
 * @group SearchPreferencesCest
 */
class SearchPreferencesCest
{

    /**
     * @param \Acceptance\ProductSearch\Zed\SearchPreferences\Tester\SearchPreferencesTester $i
     *
     * @return void
     */
    public function showListOfFilters(SearchPreferencesTester $i)
    {
        $i->amOnPage(SearchPreferencesPage::URL_LIST);
        $i->seeElement(SearchPreferencesPage::SELECTOR_SEARCH_PREFERENCES_LIST);
    }

    /**
     * @param \Acceptance\ProductSearch\Zed\SearchPreferences\Tester\SearchPreferencesTester $i
     *
     * @return void
     */
    public function addAndEditAndDeactivateAttributeToSearch(SearchPreferencesTester $i)
    {
        $attributeKey = 'foooooo';
        $i->addAttributeToSearch($attributeKey);
        $i->updateAttributeToSearch($attributeKey);
        $i->deactivateAttributeToSearch($attributeKey);
    }

    /**
     * @param \Acceptance\ProductSearch\Zed\SearchPreferences\Tester\SearchPreferencesTester $i
     *
     * @return void
     */
    public function synchronizeFilterPreferences(SearchPreferencesTester $i)
    {
        $attributeKey = 'foooooo';
        $i->addAttributeToSearch($attributeKey);

        $i->amOnPage(SearchPreferencesPage::URL_LIST);

        $i->click('#syncSearchPreferences');

        $i->canSeeCurrentUrlEquals(SearchPreferencesPage::URL_LIST);
        $i->wait(1);
        $i->canSee('Search preferences synchronization was successful.');

        // TODO: don't need to delete, after we have clean test state after each test case
        $i->deactivateAttributeToSearch($attributeKey);
    }

}
