<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Dataset\Communication\Controller;

use SprykerTest\Zed\Dataset\DatasetCommunicationTester;
use SprykerTest\Zed\Dataset\PageObject\DatasetListPage;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Dataset
 * @group Communication
 * @group Controller
 * @group DatasetEditCest
 * Add your own group annotations below this line
 */
class DatasetEditCest
{
    /**
     * @param \SprykerTest\Zed\Dataset\DatasetCommunicationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(DatasetCommunicationTester $i)
    {
        $i->amOnPage(DatasetListPage::URL);
        $i->clickDataTableEditButton();
        $i->seeBreadcrumbNavigation('Dashboard / Dataset / Edit dashboard');
    }
}
