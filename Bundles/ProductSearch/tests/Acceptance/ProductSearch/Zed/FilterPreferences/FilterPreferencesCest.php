<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\ProductSearch\Zed\FilterPreferences;

use Acceptance\ProductSearch\Zed\FilterPreferences\PageObject\FilterPreferencesPage;
use Acceptance\ProductSearch\Zed\FilterPreferences\Tester\FilterPreferencesTester;

/**
 * @group Acceptance
 * @group ProductSearch
 * @group Zed
 * @group FilterPreferences
 * @group FilterPreferencesCest
 */
class FilterPreferencesCest
{

    /**
     * @param \Acceptance\ProductSearch\Zed\FilterPreferences\Tester\FilterPreferencesTester $i
     *
     * @return void
     */
    public function showListOfFilters(FilterPreferencesTester $i)
    {
        $i->amOnPage(FilterPreferencesPage::URL_LIST);
        $i->seeElement(FilterPreferencesPage::SELECTOR_FILTER_LIST);
    }

    /**
     * @param \Acceptance\ProductSearch\Zed\FilterPreferences\Tester\FilterPreferencesTester $i
     *
     * @return void
     */
    public function createEditAndRemoveFilter(FilterPreferencesTester $i)
    {
        $id = $i->createFilter('foooooo_' . rand(1, 1000));
        $i->updateFilter($id);
        $i->deleteFilter($id);
    }

    /**
     * @skip Drag & drop functionality is not working, need to recheck later with upcoming codeception related fixes
     *
     * @param \Acceptance\ProductSearch\Zed\FilterPreferences\Tester\FilterPreferencesTester $i
     *
     * @return void
     */
    public function updateFilterOrder(FilterPreferencesTester $i)
    {
        $idFoo = $i->createFilter('foooooo_' . rand(1, 1000));
        $idBar = $i->createFilter('baaaaar_' . rand(1, 1000));

        $i->amOnPage('/product-search/filter-reorder');

        // check initial order
        $i->canSeeElement('li[data-id-product-search-attribute="' . $idFoo . '"] ~ li[data-id-product-search-attribute="' . $idBar . '"]');

        // drag and drop to change order
        $i->dragAndDrop('li[data-id-product-search-attribute="' . $idFoo . '"]', 'li[data-id-product-search-attribute="' . $idBar . '"]');

        // check order after drag and drop
        $i->canSeeElement('li[data-id-product-search-attribute="' . $idBar . '"] ~ li[data-id-product-search-attribute="' . $idFoo . '"]');

        // save
        $i->click(FilterPreferencesPage::SELECTOR_SAVE_FILTER_ORDER);
        $i->wait(1);
        $i->canSee('Success', FilterPreferencesPage::SELECTOR_ORDER_SAVE_ALERT);

        // check if it was persistent
        $i->reloadPage();
        $i->canSeeElement('li[data-id-product-search-attribute="' . $idBar . '"] ~ li[data-id-product-search-attribute="' . $idFoo . '"]');
    }

    /**
     * @param \Acceptance\ProductSearch\Zed\FilterPreferences\Tester\FilterPreferencesTester $i
     *
     * @return void
     */
    public function synchronizeFilterPreferences(FilterPreferencesTester $i)
    {
        $i->createFilter('foooooo_' . rand(1, 1000));

        $i->amOnPage(FilterPreferencesPage::URL_LIST);

        $i->click(FilterPreferencesPage::SELECTOR_SYNC_FILTERS);

        $i->canSeeCurrentUrlEquals(FilterPreferencesPage::URL_LIST);
        $i->canSee('Filter preferences synchronization was successful.');
    }

}
