<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsBlockGui\Presentation;

use SprykerTest\Zed\CmsBlockGui\PageObject\CmsBlockGuiListPage;
use SprykerTest\Zed\CmsBlockGui\PresentationTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CmsBlockGui
 * @group Presentation
 * @group CmsBlockGuiEditCest
 * Add your own group annotations below this line
 */
class CmsBlockGuiEditCest
{

    /**
     * @param \SprykerTest\Zed\CmsBlockGui\PresentationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(PresentationTester $i)
    {
        $i->amOnPage(CmsBlockGuiListPage::URL);
        $i->clickDataTableButton('Edit Block');
        $i->seeBreadcrumbNavigation('Dashboard / Content Management / Blocks / Edit CMS Block');
    }

}
