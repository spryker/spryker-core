<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Cart\Communication\Plugin;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Cart\Communication\Plugin\SkuGroupKeyPlugin;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Cart
 * @group Communication
 * @group Plugin
 * @group SkuGroupKeyPluginTest
 */
class SkuGroupKeyPluginTest extends \PHPUnit_Framework_TestCase
{

    const SKU = 'sku';

    /**
     * @return void
     */
    public function testExpandItemMustSetGroupKeyToSkuOfGivenProductWhenNoGroupKeyIsSet()
    {
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku(self::SKU);

        $changeTransfer = new CartChangeTransfer();
        $changeTransfer->addItem($itemTransfer);

        $plugin = new SkuGroupKeyPlugin();
        $plugin->expandItems($changeTransfer);

        $this->assertSame(self::SKU, $changeTransfer->getItems()[0]->getGroupKey());
    }

    /**
     * @return void
     */
    public function testExpandItemMustNotChangeGroupKeyWhenGroupKeyIsSet()
    {
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku(self::SKU);
        $itemTransfer->setGroupKey(self::SKU);

        $changeTransfer = new CartChangeTransfer();
        $changeTransfer->addItem($itemTransfer);

        $plugin = new SkuGroupKeyPlugin();
        $plugin->expandItems($changeTransfer);

        $this->assertSame(self::SKU, $changeTransfer->getItems()[0]->getGroupKey());
    }

}
