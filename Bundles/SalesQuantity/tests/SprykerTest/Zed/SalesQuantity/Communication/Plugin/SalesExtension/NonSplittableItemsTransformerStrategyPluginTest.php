<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesQuantity\Communication\Plugin\SalesExtension;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\SalesQuantity\Communication\Plugin\SalesExtension\NonSplittableItemTransformerStrategyPlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group SalesQuantity
 * @group Communication
 * @group Plugin
 * @group SalesExtension
 * @group NonSplittableItemsTransformerStrategyPluginTest
 * Add your own group annotations below this line
 */
class NonSplittableItemsTransformerStrategyPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testIsApplicable(): void
    {
        $plugin = new NonSplittableItemTransformerStrategyPlugin();
        $itemTransfer = (new ItemTransfer())->setIsQuantitySplittable(true);
        $result = $plugin->isApplicable($itemTransfer);

        $this->assertFalse($result);

        $itemTransfer->setIsQuantitySplittable(false);
        $result = $plugin->isApplicable($itemTransfer);

        $this->assertTrue($result);
    }
}
