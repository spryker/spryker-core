<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Cms\Communication\Controller;

use SprykerTest\Zed\Cms\CmsCommunicationTester;
use SprykerTest\Zed\Cms\PageObject\CmsRedirectCreatePage;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Cms
 * @group Communication
 * @group Controller
 * @group CmsRedirectCreateCest
 * Add your own group annotations below this line
 */
class CmsRedirectCreateCest
{
    /**
     * @param \SprykerTest\Zed\Cms\CmsCommunicationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(CmsCommunicationTester $i)
    {
        $i->amOnPage(CmsRedirectCreatePage::URL);
        $i->seeBreadcrumbNavigation('Dashboard / Content Management / Redirects / Create new CMS Redirect');
    }
}
