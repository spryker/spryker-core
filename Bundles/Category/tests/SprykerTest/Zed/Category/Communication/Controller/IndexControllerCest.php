<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Category\Communication\Controller;

use SprykerTest\Zed\Category\CategoryCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Category
 * @group Communication
 * @group Controller
 * @group IndexControllerCest
 * Add your own group annotations below this line
 */
class IndexControllerCest
{
    /**
     * @param \SprykerTest\Zed\Category\CategoryCommunicationTester $i
     *
     * @return void
     */
    public function listCategories(CategoryCommunicationTester $i)
    {
        $i->amOnPage('/category');
        $i->seeResponseCodeIs(200);
        $i->see('Category', 'h5');
    }
}
