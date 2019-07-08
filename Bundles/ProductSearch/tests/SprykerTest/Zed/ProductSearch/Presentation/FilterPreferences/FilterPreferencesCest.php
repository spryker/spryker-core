<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductSearch\Presentation\FilterPreferences;

use SprykerTest\Zed\ProductSearch\PageObject\FilterPreferencesPage;
use SprykerTest\Zed\ProductSearch\ProductSearchPresentationTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductSearch
 * @group Presentation
 * @group FilterPreferences
 * @group FilterPreferencesCest
 * Add your own group annotations below this line
 */
class FilterPreferencesCest
{
    /**
     * @param \SprykerTest\Zed\ProductSearch\ProductSearchPresentationTester $i
     *
     * @return void
     */
    public function showListOfFilters(ProductSearchPresentationTester $i)
    {
        $i->amOnPage(FilterPreferencesPage::URL_LIST);
        $i->seeElement(FilterPreferencesPage::SELECTOR_FILTER_LIST);
    }

    /**
     * @param \SprykerTest\Zed\ProductSearch\ProductSearchPresentationTester $i
     *
     * @return void
     */
    public function createEditAndRemoveFilter(ProductSearchPresentationTester $i)
    {
        $id = $i->createFilter('foooooo_' . rand(1, 1000));
        $i->updateFilter($id);
        $i->deleteFilter($id);
    }

    /**
     * @param \SprykerTest\Zed\ProductSearch\ProductSearchPresentationTester $i
     *
     * @return void
     */
    public function updateFilterOrder(ProductSearchPresentationTester $i)
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
     * @param \SprykerTest\Zed\ProductSearch\ProductSearchPresentationTester $i
     *
     * @return void
     */
    public function synchronizeFilterPreferences(ProductSearchPresentationTester $i)
    {
        $i->createFilter('foooooo_' . rand(1, 1000));

        $i->amOnPage(FilterPreferencesPage::URL_LIST);

        $i->click(FilterPreferencesPage::SELECTOR_SYNC_FILTERS);

        $i->wait(1);
        $i->canSeeCurrentUrlEquals(FilterPreferencesPage::URL_LIST);
        $i->canSee('Filter preferences synchronization was successful.');
    }
}
