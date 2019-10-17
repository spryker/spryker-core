<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Acl\Communication\Controller;

use SprykerTest\Zed\Acl\AclCommunicationTester;
use SprykerTest\Zed\Acl\PageObject\AclGroupListPage;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Acl
 * @group Communication
 * @group Controller
 * @group AclGroupListCest
 * Add your own group annotations below this line
 */
class AclGroupListCest
{
    /**
     * @param \SprykerTest\Zed\Acl\AclCommunicationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(AclCommunicationTester $i)
    {
        $i->amOnPage(AclGroupListPage::URL);
        $i->seeBreadcrumbNavigation('Dashboard / Users Control / Groups');
    }
}
