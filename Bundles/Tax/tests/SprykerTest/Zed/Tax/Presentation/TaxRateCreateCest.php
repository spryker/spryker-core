<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Tax\Presentation;

use SprykerTest\Zed\Tax\PageObject\TaxRateCreatePage;
use SprykerTest\Zed\Tax\TaxPresentationTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Tax
 * @group Presentation
 * @group TaxRateCreateCest
 * Add your own group annotations below this line
 */
class TaxRateCreateCest
{

    /**
     * @param \SprykerTest\Zed\Tax\TaxPresentationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(TaxPresentationTester $i)
    {
        $i->amOnPage(TaxRateCreatePage::URL);

        $i->seeBreadcrumbNavigation('Dashboard / Taxes / Tax Rates / Create New Tax Rate');
    }

}
