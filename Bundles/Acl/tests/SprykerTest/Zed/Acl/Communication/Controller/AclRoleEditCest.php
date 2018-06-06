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
 * @group SprykerTest
 * @group Zed
 * @group Acl
 * @group Communication
 * @group Controller
 * @group AclRoleEditCest
 * Add your own group annotations below this line
 */
class AclRoleEditCest
{
    /**
     * @param \SprykerTest\Zed\Acl\AclCommunicationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(AclCommunicationTester $i)
    {
        $i->listDataTable(AclRoleListPage::URL . '/table');
        $i->clickDataTableEditButton();
        $i->seeBreadcrumbNavigation('Dashboard / Users Control / Roles / Edit Role');
    }
}
