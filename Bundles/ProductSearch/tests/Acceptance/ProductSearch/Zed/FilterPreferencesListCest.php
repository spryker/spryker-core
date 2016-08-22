<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\ProductSearch\Zed;

use Acceptance\ProductSearch\Zed\PageObject\FilterPreferencesListPage;
use Acceptance\ProductSearch\Zed\Tester\FilterPreferencesTester;

/**
 * @group Acceptance
 * @group ProductSearch
 * @group Zed
 * @group FilterPreferencesList
 */
class FilterPreferencesListCest
{

    /**
     * @param \Acceptance\ProductSearch\Zed\Tester\FilterPreferencesTester $i
     *
     * @return void
     */
    public function showListOfFilters(FilterPreferencesTester $i)
    {
        $i->amOnPage(FilterPreferencesListPage::URL);
        $i->seeElement(FilterPreferencesListPage::SELECTOR_TABLE);
    }

}
