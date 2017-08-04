<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsBlockGui\Presentation;

use SprykerTest\Zed\CmsBlockGui\CmsBlockGuiPresentationTester;
use SprykerTest\Zed\CmsBlockGui\PageObject\CmsBlockGuiCreatePage;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CmsBlockGui
 * @group Presentation
 * @group CmsBlockGuiCreateCest
 * Add your own group annotations below this line
 */
class CmsBlockGuiCreateCest
{

    /**
     * @param \SprykerTest\Zed\CmsBlockGui\CmsBlockGuiPresentationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(CmsBlockGuiPresentationTester $i)
    {
        $i->amOnPage(CmsBlockGuiCreatePage::URL);
        $i->seeBreadcrumbNavigation('Dashboard / Content Management / Blocks / Create new CMS Block');
    }

}
