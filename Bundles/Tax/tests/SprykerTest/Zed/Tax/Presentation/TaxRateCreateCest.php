<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Tax\Presentation;

use SprykerTest\Zed\Tax\PageObject\TaxRateCreatePage;
use SprykerTest\Zed\Tax\PresentationTester;

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
     * @param \SprykerTest\Zed\Tax\PresentationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(PresentationTester $i)
    {
        $i->amOnPage(TaxRateCreatePage::URL);

        $i->seeBreadcrumbNavigation('Dashboard / Taxes / Tax Rates / Create New Tax Rate');
    }

}
