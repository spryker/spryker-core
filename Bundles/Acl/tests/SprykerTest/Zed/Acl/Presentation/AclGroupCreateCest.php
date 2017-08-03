<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Acl\Presentation;

use SprykerTest\Zed\Acl\AclPresentationTester;
use SprykerTest\Zed\Acl\PageObject\AclGroupCreatePage;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Acl
 * @group Presentation
 * @group AclGroupCreateCest
 * Add your own group annotations below this line
 */
class AclGroupCreateCest
{

    /**
     * @param \SprykerTest\Zed\Acl\AclPresentationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(AclPresentationTester $i)
    {
        $i->amOnPage(AclGroupCreatePage::URL);
        $i->seeBreadcrumbNavigation('Dashboard / Users Control / Groups / Create new Group');
    }

}
