<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesQuantity\Communication\Plugin\DiscountExtension;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\SalesQuantity\Communication\Plugin\DiscountExtension\NonSplittableDiscountableItemTransformerStrategyPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesQuantity
 * @group Communication
 * @group Plugin
 * @group DiscountExtension
 * @group NonSplittableDiscountableItemTransformerStrategyPluginTest
 * Add your own group annotations below this line
 */
class NonSplittableDiscountableItemTransformerStrategyPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testIsApplicable(): void
    {
        $plugin = new NonSplittableDiscountableItemTransformerStrategyPlugin();
        $itemTransfer = (new ItemTransfer())->setIsQuantitySplittable(true);
        $discountableItemTransfer = (new DiscountableItemTransfer())->setOriginalItem($itemTransfer);
        $result = $plugin->isApplicable($discountableItemTransfer);

        $this->assertFalse($result);

        $itemTransfer->setIsQuantitySplittable(false);
        $discountableItemTransfer = (new DiscountableItemTransfer())->setOriginalItem($itemTransfer);
        $result = $plugin->isApplicable($discountableItemTransfer);

        $this->assertTrue($result);
    }
}
