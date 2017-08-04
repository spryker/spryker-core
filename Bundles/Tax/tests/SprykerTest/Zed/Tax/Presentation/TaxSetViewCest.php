<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Tax\Presentation;

use SprykerTest\Zed\Tax\PageObject\TaxSetListPage;
use SprykerTest\Zed\Tax\TaxPresentationTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Tax
 * @group Presentation
 * @group TaxSetViewCest
 * Add your own group annotations below this line
 */
class TaxSetViewCest
{

    /**
     * @param \SprykerTest\Zed\Tax\TaxPresentationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(TaxPresentationTester $i)
    {
        $i->amOnPage(TaxSetListPage::URL);
        $i->clickDataTableViewButton();
        $i->seeBreadcrumbNavigation('Dashboard / Taxes / Tax Sets / View Tax Set');
    }

}
