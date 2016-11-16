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
        $id = $i->createFilter('foooooo');
        $i->updateFilter($id);
        $i->deleteFilter($id);
    }

    /**
     * @param \Acceptance\ProductSearch\Zed\FilterPreferences\Tester\FilterPreferencesTester $i
     *
     * @return void
     */
    public function updateFilterOrder(FilterPreferencesTester $i)
    {
        $idFoo = $i->createFilter('foooooo');
        $idBar = $i->createFilter('baaaaar');

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

        // TODO: don't need to delete, after we have clean test state after each test case
        $i->deleteFilter($idFoo);
        $i->deleteFilter($idBar);
    }

    /**
     * @param \Acceptance\ProductSearch\Zed\FilterPreferences\Tester\FilterPreferencesTester $i
     *
     * @return void
     */
    public function synchronizeFilterPreferences(FilterPreferencesTester $i)
    {
        $id = $i->createFilter('foooooo');

        $i->amOnPage(FilterPreferencesPage::URL_LIST);

        $i->click(FilterPreferencesPage::SELECTOR_SYNC_FILTERS);

        $i->canSeeCurrentUrlEquals(FilterPreferencesPage::URL_LIST);
        $i->canSee('Filter preferences synchronization was successful.');

        // TODO: don't need to delete, after we have clean test state after each test case
        $i->deleteFilter($id);
    }

}
