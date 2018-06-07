<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Tax\Communication\Controller;

use SprykerTest\Zed\Tax\PageObject\TaxRateCreatePage;
use SprykerTest\Zed\Tax\TaxCommunicationTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Tax
 * @group Communication
 * @group Controller
 * @group TaxRateCreateCest
 * Add your own group annotations below this line
 */
class TaxRateCreateCest
{
    /**
     * @param \SprykerTest\Zed\Tax\TaxCommunicationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(TaxCommunicationTester $i)
    {
        $i->amOnPage(TaxRateCreatePage::URL);
        $i->seeBreadcrumbNavigation('Dashboard / Taxes / Tax Rates / Create Tax Rate');
    }
}
