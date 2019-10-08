<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Tax\Communication\Controller;

use SprykerTest\Zed\Tax\PageObject\TaxSetCreatePage;
use SprykerTest\Zed\Tax\TaxCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Tax
 * @group Communication
 * @group Controller
 * @group TaxSetCreateCest
 * Add your own group annotations below this line
 */
class TaxSetCreateCest
{
    public const TAX_RATE_NAME = 'Tax Rate Name';

    /**
     * @param \SprykerTest\Zed\Tax\TaxCommunicationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(TaxCommunicationTester $i)
    {
        $i->amOnPage(TaxSetCreatePage::URL);
        $i->seeBreadcrumbNavigation('Dashboard / Taxes / Tax Sets / Create Tax Set');
    }

    /**
     * @param \SprykerTest\Zed\Tax\TaxCommunicationTester $i
     *
     * @return void
     */
    public function iCanAddANewTaxSet(TaxCommunicationTester $i)
    {
        $i->amOnPage(TaxSetCreatePage::URL);

        $i->fillField(['name' => 'tax_set[name]'], static::TAX_RATE_NAME);
        $i->click('//div[@id="tax_set_taxRates"]//input[1]');
        $i->click('Save');

        $i->seeInField(['name' => 'tax_set[name]'], static::TAX_RATE_NAME);

        $i->seeResponseCodeIs(200);
    }
}
