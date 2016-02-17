<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Cart\Communication\Plugin;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Kernel\Communication\Factory;
use Spryker\Zed\Kernel\Locator;
use Spryker\Zed\Cart\Communication\Plugin\SkuGroupKeyPlugin;

/**
 * @group Spryker
 * @group Zed
 * @group Cart
 * @group Communication
 * @group SkuGroupKeyPlugin
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

        $plugin = new SkuGroupKeyPlugin(new Factory('Cart'), Locator::getInstance());
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

        $plugin = new SkuGroupKeyPlugin(new Factory('Cart'), Locator::getInstance());
        $plugin->expandItems($changeTransfer);

        $this->assertSame(self::SKU, $changeTransfer->getItems()[0]->getGroupKey());
    }

}
