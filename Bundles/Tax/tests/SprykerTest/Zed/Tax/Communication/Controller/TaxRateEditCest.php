<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Tax\Communication\Controller;

use SprykerTest\Zed\Tax\PageObject\TaxRateListPage;
use SprykerTest\Zed\Tax\TaxCommunicationTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Tax
 * @group Communication
 * @group Controller
 * @group TaxRateEditCest
 * Add your own group annotations below this line
 */
class TaxRateEditCest
{
    /**
     * @param \SprykerTest\Zed\Tax\TaxCommunicationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(TaxCommunicationTester $i)
    {
        $i->listDataTable(TaxRateListPage::TABLE_DATA_URL);
        $i->clickDataTableEditButton();
        $i->seeBreadcrumbNavigation('Dashboard / Taxes / Tax Rates / Edit Tax Rate');
    }

    /**
     * @param \SprykerTest\Zed\Tax\TaxCommunicationTester $i
     *
     * @return void
     */
    public function dataIsProvided(TaxCommunicationTester $i)
    {
        $i->listDataTable(TaxRateListPage::TABLE_DATA_URL);
        $i->clickDataTableEditButton();

        $name = $i->grabValueFrom('[name="tax_rate[name]"]');
        $i->assertNotEmpty($name);
        $country = $i->grabValueFrom('[name="tax_rate[fkCountry]"]');
        $i->assertNotEmpty($country);
        $country = $i->grabValueFrom('[name="tax_rate[rate]"]');
        $i->assertNotEmpty($country);
    }
}
