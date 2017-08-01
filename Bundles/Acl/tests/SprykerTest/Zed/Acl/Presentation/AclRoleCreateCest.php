<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Acl\Presentation;

use SprykerTest\Zed\Acl\PageObject\AclRoleCreatePage;
use SprykerTest\Zed\Acl\PresentationTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Acl
 * @group Presentation
 * @group AclRoleCreateCest
 * Add your own group annotations below this line
 */
class AclRoleCreateCest
{

    /**
     * @param \SprykerTest\Zed\Acl\PresentationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(PresentationTester $i)
    {
        $i->amOnPage(AclRoleCreatePage::URL);
        $i->seeBreadcrumbNavigation('Dashboard / Users Control / Roles / Create new Role');
    }

}
