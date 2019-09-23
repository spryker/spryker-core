<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Acl\Communication\Controller;

use SprykerTest\Zed\Acl\AclCommunicationTester;
use SprykerTest\Zed\Acl\PageObject\AclRoleCreatePage;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Acl
 * @group Communication
 * @group Controller
 * @group AclRoleCreateCest
 * Add your own group annotations below this line
 */
class AclRoleCreateCest
{
    /**
     * @param \SprykerTest\Zed\Acl\AclCommunicationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(AclCommunicationTester $i)
    {
        $i->amOnPage(AclRoleCreatePage::URL);
        $i->seeBreadcrumbNavigation('Dashboard / Users Control / Roles / Create new Role');
    }
}
