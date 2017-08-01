<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Acl\Presentation;

use SprykerTest\Zed\Acl\PageObject\AclGroupCreatePage;
use SprykerTest\Zed\Acl\PresentationTester;

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
     * @param \SprykerTest\Zed\Acl\PresentationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(PresentationTester $i)
    {
        $i->amOnPage(AclGroupCreatePage::URL);
        $i->seeBreadcrumbNavigation('Dashboard / Users Control / Groups / Create new Group');
    }

}
