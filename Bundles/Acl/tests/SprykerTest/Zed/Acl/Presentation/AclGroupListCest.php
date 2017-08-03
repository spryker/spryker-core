<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Acl\Presentation;

use SprykerTest\Zed\Acl\AclPresentationTester;
use SprykerTest\Zed\Acl\PageObject\AclGroupListPage;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Acl
 * @group Presentation
 * @group AclGroupListCest
 * Add your own group annotations below this line
 */
class AclGroupListCest
{

    /**
     * @param \SprykerTest\Zed\Acl\AclPresentationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(AclPresentationTester $i)
    {
        $i->amOnPage(AclGroupListPage::URL);
        $i->seeBreadcrumbNavigation('Dashboard / Users Control / Groups');
    }

}
