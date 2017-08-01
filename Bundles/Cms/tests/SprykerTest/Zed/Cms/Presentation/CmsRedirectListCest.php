<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Cms\Presentation;

use SprykerTest\Zed\Cms\CmsPresentationTester;
use SprykerTest\Zed\Cms\PageObject\CmsRedirectListPage;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Cms
 * @group Presentation
 * @group CmsRedirectListCest
 * Add your own group annotations below this line
 */
class CmsRedirectListCest
{

    /**
     * @param \SprykerTest\Zed\Cms\CmsPresentationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(CmsPresentationTester $i)
    {
        $i->amOnPage(CmsRedirectListPage::URL);
        $i->seeBreadcrumbNavigation('Dashboard / Content Management / Redirects');
    }

}
