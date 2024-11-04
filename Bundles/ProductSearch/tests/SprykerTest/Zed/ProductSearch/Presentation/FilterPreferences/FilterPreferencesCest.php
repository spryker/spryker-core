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
 *
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
    public function _before(ProductSearchPresentationTester $i): void
    {
        $i->amZed();
        $i->amLoggedInUser();
    }

    /**
     * @param \SprykerTest\Zed\ProductSearch\ProductSearchPresentationTester $i
     *
     * @return void
     */
    public function showListOfFilters(ProductSearchPresentationTester $i): void
    {
        $i->amOnPage(FilterPreferencesPage::URL_LIST);
        $i->seeElement(FilterPreferencesPage::SELECTOR_FILTER_LIST);
    }

    /**
     * @param \SprykerTest\Zed\ProductSearch\ProductSearchPresentationTester $i
     *
     * @return void
     */
    public function createEditAndRemoveFilter(ProductSearchPresentationTester $i): void
    {
        $id = $i->createFilter('foooooo_' . rand(1, 1000));
        $i->updateFilter($id);
        $i->deleteFilter($id);
    }

    /**
     * @skip This test was temporarily skipped due to flikerness. See {@link https://spryker.atlassian.net/browse/CC-25718} for details
     *
     * @param \SprykerTest\Zed\ProductSearch\ProductSearchPresentationTester $i
     *
     * @return void
     */
    public function updateFilterOrder(ProductSearchPresentationTester $i): void
    {
        $idFoo = $i->createFilter('foooooo_' . rand(1, 1000));
        $idBar = $i->createFilter('baaaaar_' . rand(1, 1000));

        $i->amOnPage('/product-search/filter-reorder');

        // check initial order
        $i->canSeeElement('li[data-id-product-search-attribute="' . $idFoo . '"] ~ li[data-id-product-search-attribute="' . $idBar . '"]');

        // drag and drop to change order
        $i->dragAndDrop('li[data-id-product-search-attribute="' . $idFoo . '"]', 'li[data-id-product-search-attribute="' . $idBar . '"]');
        $i->dragAndDrop('li[data-id-product-search-attribute="' . $idBar . '"]', 'li[data-id-product-search-attribute="' . $idFoo . '"]');

        // check order after drag and drop
        $i->canSeeElement('li[data-id-product-search-attribute="' . $idBar . '"] ~ li[data-id-product-search-attribute="' . $idFoo . '"]');

        // save
        $i->click(FilterPreferencesPage::SELECTOR_SAVE_FILTER_ORDER);
        $i->wait(5);
        $i->canSee('Success', FilterPreferencesPage::SELECTOR_ORDER_SAVE_ALERT);

        // check if it was persistent
        $i->reloadPage();
        $i->canSeeElement('li[data-id-product-search-attribute="' . $idBar . '"] ~ li[data-id-product-search-attribute="' . $idFoo . '"]');
    }

    /**
     * @skip This test was temporarily skipped due to flikerness. See {@link https://spryker.atlassian.net/browse/CC-25718} for details
     *
     * @param \SprykerTest\Zed\ProductSearch\ProductSearchPresentationTester $i
     *
     * @return void
     */
    public function synchronizeFilterPreferences(ProductSearchPresentationTester $i): void
    {
        $i->createFilter('foooooo_' . rand(1, 1000));

        $i->amOnPage(FilterPreferencesPage::URL_LIST);

        $i->click(FilterPreferencesPage::SELECTOR_SYNC_FILTERS);
        $i->wait(15);

        $i->canSeeCurrentUrlEquals(FilterPreferencesPage::URL_LIST);
        $i->canSee('Filter preferences synchronization was successful.');
    }
}
