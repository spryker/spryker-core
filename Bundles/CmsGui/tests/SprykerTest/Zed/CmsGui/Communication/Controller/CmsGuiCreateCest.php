<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsGui\Communication\Controller;

use SprykerTest\Zed\CmsGui\CmsGuiCommunicationTester;
use SprykerTest\Zed\CmsGui\PageObject\CmsGuiCreatePage;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CmsGui
 * @group Communication
 * @group Controller
 * @group CmsGuiCreateCest
 * Add your own group annotations below this line
 */
class CmsGuiCreateCest
{
    /**
     * @param \SprykerTest\Zed\CmsGui\CmsGuiCommunicationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(CmsGuiCommunicationTester $i)
    {
        $i->amOnPage(CmsGuiCreatePage::URL);
        $i->seeBreadcrumbNavigation('Dashboard / Content Management / Pages / Create new CMS Page');
    }
}
