<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ConfigurableBundleCart;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ConfiguredBundleItemTransfer;
use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Client\ConfigurableBundleCart\Adder\ConfiguredBundleCartAdder;
use Spryker\Client\ConfigurableBundleCart\Dependency\Client\ConfigurableBundleCartToCartClientBridge;
use Spryker\Client\ConfigurableBundleCart\Dependency\Service\ConfigurableBundleCartToConfigurableBundleCartServiceBridge;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ConfigurableBundleCart
 * @group ConfigurableBundleCartAdderTest
 * Add your own group annotations below this line
 */
class ConfigurableBundleCartAdderTest extends Unit
{
    protected const FAKE_CONFIGURABLE_BUNDLE_NAME = 'FAKE_CONFIGURABLE_BUNDLE_NAME';

    protected const FAKE_CONFIGURABLE_BUNDLE_UUID_1 = 'FAKE_CONFIGURABLE_BUNDLE_UUID_1';

    protected const FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1 = 'FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1';
    protected const FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_2 = 'FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_2';

    /**
     * @var \SprykerTest\Client\ConfigurableBundleCart\ConfigurableBundleCartClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAddConfiguredBundleToCartAddConfiguredBundleToQuote(): void
    {
        // Arrange
        $createConfiguredBundleRequestTransfer = (new CreateConfiguredBundleRequestTransfer())
            ->setConfiguredBundle(
                (new ConfiguredBundleTransfer())
                    ->setQuantity(1)
                    ->setTemplate((new ConfigurableBundleTemplateTransfer())
                        ->setUuid(static::FAKE_CONFIGURABLE_BUNDLE_UUID_1)
                        ->setName(static::FAKE_CONFIGURABLE_BUNDLE_NAME))
            )
            ->addItem(
                (new ItemTransfer())
                    ->setConfiguredBundleItem(
                        (new ConfiguredBundleItemTransfer())
                            ->setSlot((new ConfigurableBundleTemplateSlotTransfer())->setUuid(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1))
                    )
            )
            ->addItem(
                (new ItemTransfer())
                    ->setConfiguredBundleItem(
                        (new ConfiguredBundleItemTransfer())
                            ->setSlot((new ConfigurableBundleTemplateSlotTransfer())->setUuid(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_2))
                    )
            );

        $cartClientMock = $this->createCartClientMock();

        $cartClientMock
            ->method('addToCart')
            ->willReturn((new QuoteResponseTransfer())->setIsSuccessful(true));

        $configuredBundleCartAdderMock = $this->createConfiguredBundleCartAdderMock($cartClientMock);

        // Act
        $quoteResponseTransfer = $configuredBundleCartAdderMock->addConfiguredBundleToCart($createConfiguredBundleRequestTransfer);

        // Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testAddConfiguredBundleToCartThrowsExceptionRequiredTemplateUuid(): void
    {
        // Arrange
        $createConfiguredBundleRequestTransfer = (new CreateConfiguredBundleRequestTransfer())
            ->setConfiguredBundle(
                (new ConfiguredBundleTransfer())
                    ->setQuantity(1)
                    ->setTemplate((new ConfigurableBundleTemplateTransfer())
                        ->setName(static::FAKE_CONFIGURABLE_BUNDLE_NAME))
            )
            ->addItem(
                (new ItemTransfer())
                    ->setConfiguredBundleItem(
                        (new ConfiguredBundleItemTransfer())
                            ->setSlot((new ConfigurableBundleTemplateSlotTransfer())->setUuid(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1))
                    )
            )
            ->addItem(
                (new ItemTransfer())
                    ->setConfiguredBundleItem(
                        (new ConfiguredBundleItemTransfer())
                            ->setSlot((new ConfigurableBundleTemplateSlotTransfer())->setUuid(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_2))
                    )
            );

        $configuredBundleCartAdderMock = $this->createConfiguredBundleCartAdderMock($this->createCartClientMock());

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $configuredBundleCartAdderMock->addConfiguredBundleToCart($createConfiguredBundleRequestTransfer);
    }

    /**
     * @return void
     */
    public function testAddConfiguredBundleToCartThrowsExceptionRequiredSlotUuid(): void
    {
        // Arrange
        $createConfiguredBundleRequestTransfer = (new CreateConfiguredBundleRequestTransfer())
            ->setConfiguredBundle(
                (new ConfiguredBundleTransfer())
                    ->setQuantity(1)
                    ->setTemplate((new ConfigurableBundleTemplateTransfer())
                        ->setUuid(static::FAKE_CONFIGURABLE_BUNDLE_UUID_1)
                        ->setName(static::FAKE_CONFIGURABLE_BUNDLE_NAME))
            )
            ->addItem(
                (new ItemTransfer())
                    ->setConfiguredBundleItem(
                        (new ConfiguredBundleItemTransfer())
                            ->setSlot((new ConfigurableBundleTemplateSlotTransfer())->setUuid(null))
                    )
            )
            ->addItem(
                (new ItemTransfer())
                    ->setConfiguredBundleItem(
                        (new ConfiguredBundleItemTransfer())
                            ->setSlot((new ConfigurableBundleTemplateSlotTransfer())->setUuid(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_2))
                    )
            );

        $configuredBundleCartAdderMock = $this->createConfiguredBundleCartAdderMock($this->createCartClientMock());

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $configuredBundleCartAdderMock->addConfiguredBundleToCart($createConfiguredBundleRequestTransfer);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ConfigurableBundleCart\Dependency\Client\ConfigurableBundleCartToCartClientBridge
     */
    protected function createCartClientMock(): ConfigurableBundleCartToCartClientBridge
    {
        return $this->getMockBuilder(ConfigurableBundleCartToCartClientBridge::class)
            ->onlyMethods([
                'addToCart',
            ])
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ConfigurableBundleCart\Dependency\Client\ConfigurableBundleCartToCartClientBridge $cartClientMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ConfigurableBundleCart\Adder\ConfiguredBundleCartAdder
     */
    protected function createConfiguredBundleCartAdderMock($cartClientMock): ConfiguredBundleCartAdder
    {
        return $this->getMockBuilder(ConfiguredBundleCartAdder::class)
            ->setConstructorArgs([
                $cartClientMock,
                new ConfigurableBundleCartToConfigurableBundleCartServiceBridge(
                    $this->tester->getLocator()->configurableBundleCart()->service()
                ),
            ])
            ->setMethods(null)
            ->getMock();
    }
}
