<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Cms\Presentation;

use SprykerTest\Zed\Cms\CmsPresentationTester;
use SprykerTest\Zed\Cms\PageObject\CmsRedirectCreatePage;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Cms
 * @group Presentation
 * @group CmsRedirectCreateCest
 * Add your own group annotations below this line
 */
class CmsRedirectCreateCest
{

    /**
     * @param \SprykerTest\Zed\Cms\CmsPresentationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(CmsPresentationTester $i)
    {
        $i->amOnPage(CmsRedirectCreatePage::URL);
        $i->seeBreadcrumbNavigation('Dashboard / Content Management / Redirects / Create new CMS Redirect');
    }

}
