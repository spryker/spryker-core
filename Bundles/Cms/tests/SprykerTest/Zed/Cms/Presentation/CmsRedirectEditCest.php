<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Cms\Presentation;

use SprykerTest\Zed\Cms\PageObject\CmsRedirectListPage;
use SprykerTest\Zed\Cms\PresentationTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Cms
 * @group Presentation
 * @group CmsRedirectEditCest
 * Add your own group annotations below this line
 */
class CmsRedirectEditCest
{

    /**
     * @param \SprykerTest\Zed\Cms\PresentationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(PresentationTester $i)
    {
        $i->amOnPage(CmsRedirectListPage::URL);
        $i->haveUrlRedirect();
        $i->wait(2);
        $i->clickDataTableEditButton();
        $i->seeBreadcrumbNavigation('Dashboard / Content Management / Redirects / Edit CMS Redirect');
    }

}
