<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesConfigurableBundle\Communication\Plugin\CartReorder;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleItemTransfer;
use ReflectionClass;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\Messenger\Business\Model\InMemoryMessageTray;
use Spryker\Zed\SalesConfigurableBundle\Communication\Plugin\CartReorder\ConfiguredBundleCartPostReorderPlugin;
use SprykerTest\Zed\SalesConfigurableBundle\SalesConfigurableBundleCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesConfigurableBundle
 * @group Communication
 * @group Plugin
 * @group CartReorder
 * @group ConfiguredBundleCartPostReorderPluginTest
 * Add your own group annotations below this line
 */
class ConfiguredBundleCartPostReorderPluginTest extends Unit
{
    /**
     * @uses \Spryker\Zed\SalesConfigurableBundle\Communication\Adder\FlashMessageAdder::GLOSSARY_KEY_CONFIGURED_BUNDLE_ITEMS_ADDED_TO_CART_SUCCESS
     *
     * @var string
     */
    protected const GLOSSARY_KEY_CONFIGURED_BUNDLE_ITEMS_ADDED_TO_CART_SUCCESS = 'sales_configured_bundle.success.items_added_to_cart_as_individual_products';

    /**
     * @var \SprykerTest\Zed\SalesConfigurableBundle\SalesConfigurableBundleCommunicationTester
     */
    protected SalesConfigurableBundleCommunicationTester $tester;

    /**
     * @return void
     */
    protected function _after(): void
    {
        parent::_after();

        $this->cleanStaticProperty();
    }

    /**
     * @return void
     */
    public function testShouldAddInfoMessageToMessenger(): void
    {
        // Arrange
        $orderTransfer = (new OrderTransfer())
            ->setItems(new ArrayObject([
                (new ItemTransfer()),
                (new ItemTransfer())->setSalesOrderConfiguredBundleItem(new SalesOrderConfiguredBundleItemTransfer()),
                (new ItemTransfer()),
            ]));

        $cartReorderTransfer = (new CartReorderTransfer())->setOrder($orderTransfer);

        // Act
        (new ConfiguredBundleCartPostReorderPlugin())->postReorder($cartReorderTransfer);

        // Assert
        $infoMessages = $this->getInfoMessages();

        $this->assertCount(1, $infoMessages);
        $this->assertSame(static::GLOSSARY_KEY_CONFIGURED_BUNDLE_ITEMS_ADDED_TO_CART_SUCCESS, $infoMessages[0]);
    }

    /**
     * @return void
     */
    public function testShouldNotAddInfoMessageToMessenger(): void
    {
        // Arrange
        $orderTransfer = (new OrderTransfer())
            ->setItems(new ArrayObject([
                (new ItemTransfer()),
                (new ItemTransfer()),
            ]));

        $cartReorderTransfer = (new CartReorderTransfer())->setOrder($orderTransfer);

        // Act
        (new ConfiguredBundleCartPostReorderPlugin())->postReorder($cartReorderTransfer);

        // Assert
        $this->assertEmpty($this->getInfoMessages());
    }

    /**
     * @return void
     */
    public function testShouldThrowNullValueException(): void
    {
        // Assert
        $this->expectException(NullValueException::class);

        // Act
        (new ConfiguredBundleCartPostReorderPlugin())->postReorder(new CartReorderTransfer());
    }

    /**
     * @return list<string>
     */
    protected function getInfoMessages(): array
    {
        return $this->tester->getLocator()->messenger()->facade()->getStoredMessages()->getInfoMessages();
    }

    /**
     * @return void
     */
    protected function cleanStaticProperty(): void
    {
        $reflectedClass = new ReflectionClass(InMemoryMessageTray::class);

        $messages = $reflectedClass->getProperty('messages');
        $messages->setAccessible(true);
        $messages->setValue(null);
    }
}
