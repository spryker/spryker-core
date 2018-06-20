<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsBlockGui\Communication\Controller;

use SprykerTest\Zed\CmsBlockGui\CmsBlockGuiCommunicationTester;
use SprykerTest\Zed\CmsBlockGui\PageObject\CmsBlockGuiCreatePage;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CmsBlockGui
 * @group Communication
 * @group Controller
 * @group CmsBlockGuiCreateCest
 * Add your own group annotations below this line
 */
class CmsBlockGuiCreateCest
{
    /**
     * @param \SprykerTest\Zed\CmsBlockGui\CmsBlockGuiCommunicationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(CmsBlockGuiCommunicationTester $i)
    {
        $i->registerCmsBlockStoreRelationFormTypePlugin();
        $i->amOnPage(CmsBlockGuiCreatePage::URL);
        $i->seeBreadcrumbNavigation('Dashboard / Content Management / Blocks / Create new CMS Block');
    }
}
