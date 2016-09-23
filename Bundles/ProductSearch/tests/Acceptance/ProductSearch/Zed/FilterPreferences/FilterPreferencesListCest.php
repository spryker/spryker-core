<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\ProductSearch\Zed\FilterPreferences;

use Acceptance\ProductSearch\Zed\FilterPreferences\PageObject\CreateFilterPage;
use Acceptance\ProductSearch\Zed\FilterPreferences\PageObject\FilterPreferencesListPage;
use Acceptance\ProductSearch\Zed\Tester\ProductSearchTester;

/**
 * @group Acceptance
 * @group ProductSearch
 * @group Zed
 * @group FilterPreferencesList
 */
class FilterPreferencesListCest
{

    /**
     * @param \Acceptance\ProductSearch\Zed\Tester\ProductSearchTester $i
     *
     * @return void
     */
    public function showListOfFilters(ProductSearchTester $i)
    {
        $i->amOnPage(FilterPreferencesListPage::URL);
        $i->seeElement(FilterPreferencesListPage::SELECTOR_TABLE);
    }

    /**
     * @param \Acceptance\ProductSearch\Zed\Tester\ProductSearchTester $i
     *
     * @return void
     */
    public function createFilter(ProductSearchTester $i)
    {
        $i->amOnPage(CreateFilterPage::URL);
        $this->fillAttributeForm($i);

        $i->click('#attributeForm_submit');
        $i->wait(5);
        $i->canSeeCurrentUrlMatches('/' . preg_quote('/product-search/filter-preferences/view?id=', '/') . '\d+/');
    }

    /**
     * @param ProductSearchTester $i
     *
     * @return void
     */
    protected function fillAttributeForm(ProductSearchTester $i)
    {
        $i->fillField('#attributeForm_key', 'foooo');
        $i->selectOption('#attributeForm_filter_type', 'multi-select');

        $i->fillField('.name-translation', 'Foooo');
        $i->click('.name-translation ~ span > button');
    }

}
