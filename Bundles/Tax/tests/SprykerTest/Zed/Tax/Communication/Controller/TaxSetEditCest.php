<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Tax\Communication\Controller;

use SprykerTest\Zed\Tax\PageObject\TaxSetListPage;
use SprykerTest\Zed\Tax\TaxCommunicationTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Tax
 * @group Communication
 * @group Controller
 * @group TaxSetEditCest
 * Add your own group annotations below this line
 */
class TaxSetEditCest
{
    /**
     * @param \SprykerTest\Zed\Tax\TaxCommunicationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(TaxCommunicationTester $i)
    {
        $i->listDataTable(TaxSetListPage::DATA_TABLE_URL);
        $i->clickDataTableEditButton();
        $i->seeBreadcrumbNavigation('Dashboard / Taxes / Tax Sets / Edit Tax Set');
    }

    /**
     * @param \SprykerTest\Zed\Tax\TaxCommunicationTester $i
     *
     * @return void
     */
    public function dataIsProvided(TaxCommunicationTester $i)
    {
        $i->listDataTable(TaxSetListPage::DATA_TABLE_URL);
        $i->clickDataTableEditButton();

        $name = $i->grabValueFrom('[name="tax_set[name]"]');
        $i->assertNotEmpty($name);
    }
}
