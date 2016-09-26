<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Acceptance\ProductOption\Zed;

use Acceptance\ProductOption\Zed\Tester\ProductOptionTest;

/**
 * @group Acceptance
 * @group Discount
 * @group Zed
 * @group ProductOptionCreateCest
 */
class ProductOptionCreateCest
{

    /**
     * @param \Acceptance\ProductOption\Zed\Tester\ProductOptionTest $i
     */
    public function testCreateProductOptionGroupWithSingleItem(ProductOptionTest $i)
    {
        $i->wantTo('Create single option group with one option');
        $i->expect('Option group with options created');

        $i->amLoggedInUser();

        $i->amOnPage(ProductOptionCreatePage::URL);

    }

}
