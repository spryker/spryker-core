<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Acl\Communication\Controller;

use SprykerTest\Zed\Acl\AclCommunicationTester;
use SprykerTest\Zed\Acl\PageObject\AclRoleListPage;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Acl
 * @group Communication
 * @group Controller
 * @group AclRoleListCest
 * Add your own group annotations below this line
 */
class AclRoleListCest
{
    /**
     * @param \SprykerTest\Zed\Acl\AclCommunicationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(AclCommunicationTester $i)
    {
        $i->amOnPage(AclRoleListPage::URL);
        $i->seeBreadcrumbNavigation('Dashboard / Users Control / Roles');
    }
}
