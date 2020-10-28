<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ConfigurableBundleCartsRestApi\Business\ConfigurableBundleCartsRestApiFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\Cart\CartDependencyProvider;
use Spryker\Zed\Cart\Communication\Plugin\SkuGroupKeyPlugin;
use Spryker\Zed\ConfigurableBundleCart\Communication\Plugin\Cart\ConfiguredBundleGroupKeyItemExpanderPlugin;
use Spryker\Zed\ConfigurableBundleCart\Communication\Plugin\Cart\ConfiguredBundleQuantityPerSlotItemExpanderPlugin;
use Spryker\Zed\ConfigurableBundleCart\Communication\Plugin\Cart\ConfiguredBundleQuantityPostSavePlugin;
use Spryker\Zed\ConfigurableBundleCartsRestApi\Business\Checker\QuotePermissionChecker;
use Spryker\Zed\ConfigurableBundleCartsRestApi\Business\ConfigurableBundleCartsRestApiBusinessFactory;
use Spryker\Zed\ConfigurableBundleCartsRestApi\Dependency\Facade\ConfigurableBundleCartsRestApiToPersistentCartFacadeBridge;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ConfigurableBundleCartsRestApi
 * @group Business
 * @group ConfigurableBundleCartsRestApiFacade
 * @group UpdateConfiguredBundleQuantityTest
 * Add your own group annotations below this line
 */
class UpdateConfiguredBundleQuantityTest extends Unit
{
    protected const FAKE_QUOTE_UUID = 'FAKE_QUOTE_UUID';

    /**
     * @uses \Spryker\Shared\CartsRestApi\CartsRestApiConfig::ERROR_IDENTIFIER_UNAUTHORIZED_CART_ACTION
     */
    protected const ERROR_IDENTIFIER_UNAUTHORIZED_CART_ACTION = 'ERROR_IDENTIFIER_UNAUTHORIZED_CART_ACTION';

    /**
     * @uses \Spryker\Shared\ConfigurableBundleCartsRestApi\ConfigurableBundleCartsRestApiConfig::ERROR_IDENTIFIER_FAILED_UPDATING_CONFIGURED_BUNDLE
     */
    protected const ERROR_IDENTIFIER_FAILED_UPDATING_CONFIGURED_BUNDLE = 'ERROR_IDENTIFIER_FAILED_UPDATING_CONFIGURED_BUNDLE';

    /**
     * @var \SprykerTest\Zed\ConfigurableBundleCartsRestApi\ConfigurableBundleCartsRestApiBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(CartDependencyProvider::CART_EXPANDER_PLUGINS, [
            new SkuGroupKeyPlugin(),
            new ConfiguredBundleQuantityPerSlotItemExpanderPlugin(),
            new ConfiguredBundleGroupKeyItemExpanderPlugin(),
        ]);

        $this->tester->setDependency(CartDependencyProvider::CART_POST_SAVE_PLUGINS, [
            new ConfiguredBundleQuantityPostSavePlugin(),
        ]);
    }

    /**
     * @return void
     */
    public function testUpdateConfiguredBundleQuantityUpdatesConfiguredBundleQuantity(): void
    {
        // Arrange
        $bundleQuantity = 2;
        $updateConfiguredBundleRequestTransfer = $this->tester->buildUpdateConfiguredBundleRequest($bundleQuantity);

        // Act
        $quoteResponseTransfer = $this->tester->getFacade()->updateConfiguredBundleQuantity($updateConfiguredBundleRequestTransfer);

        // Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());

        /** @var \Generated\Shared\Transfer\ItemTransfer $firstItemTransfer */
        $firstItemTransfer = $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(0);

        /** @var \Generated\Shared\Transfer\ItemTransfer $secondItemTransfer */
        $secondItemTransfer = $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(1);

        $this->assertSame($bundleQuantity, $firstItemTransfer->getConfiguredBundle()->getQuantity());
        $this->assertSame($bundleQuantity, $secondItemTransfer->getConfiguredBundle()->getQuantity());

        $this->assertSame(
            $bundleQuantity * $firstItemTransfer->getConfiguredBundleItem()->getQuantityPerSlot(),
            $firstItemTransfer->getQuantity()
        );
        $this->assertSame(
            $bundleQuantity * $secondItemTransfer->getConfiguredBundleItem()->getQuantityPerSlot(),
            $secondItemTransfer->getQuantity()
        );
    }

    /**
     * @return void
     */
    public function testUpdateConfiguredBundleQuantityThrowsExceptionWithEmptyGroupKeyField(): void
    {
        // Arrange
        $updateConfiguredBundleRequestTransfer = $this->tester->buildUpdateConfiguredBundleRequest();
        $updateConfiguredBundleRequestTransfer->setGroupKey(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateConfiguredBundleQuantity($updateConfiguredBundleRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateConfiguredBundleQuantityThrowsExceptionWithEmptyQuoteField(): void
    {
        // Arrange
        $updateConfiguredBundleRequestTransfer = $this->tester->buildUpdateConfiguredBundleRequest();
        $updateConfiguredBundleRequestTransfer->setQuote(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateConfiguredBundleQuantity($updateConfiguredBundleRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateConfiguredBundleQuantityThrowsExceptionWithEmptyQuoteUuidField(): void
    {
        // Arrange
        $updateConfiguredBundleRequestTransfer = $this->tester->buildUpdateConfiguredBundleRequest();
        $updateConfiguredBundleRequestTransfer->getQuote()->setUuid(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateConfiguredBundleQuantity($updateConfiguredBundleRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateConfiguredBundleQuantityThrowsExceptionWithEmptyCustomerField(): void
    {
        // Arrange
        $updateConfiguredBundleRequestTransfer = $this->tester->buildUpdateConfiguredBundleRequest();
        $updateConfiguredBundleRequestTransfer->getQuote()->setCustomer(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateConfiguredBundleQuantity($updateConfiguredBundleRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateConfiguredBundleQuantityThrowsExceptionWithEmptyQuoteCustomerReferenceField(): void
    {
        // Arrange
        $updateConfiguredBundleRequestTransfer = $this->tester->buildUpdateConfiguredBundleRequest();
        $updateConfiguredBundleRequestTransfer->getQuote()->setCustomerReference(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateConfiguredBundleQuantity($updateConfiguredBundleRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateConfiguredBundleQuantityThrowsExceptionWithEmptyCustomerReferenceField(): void
    {
        // Arrange
        $updateConfiguredBundleRequestTransfer = $this->tester->buildUpdateConfiguredBundleRequest();
        $updateConfiguredBundleRequestTransfer->getQuote()->getCustomer()->setCustomerReference(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateConfiguredBundleQuantity($updateConfiguredBundleRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateConfiguredBundleQuantityUpdatesConfiguredBundleQuantityInFakePersistentCart(): void
    {
        // Arrange
        $updateConfiguredBundleRequestTransfer = $this->tester->buildUpdateConfiguredBundleRequest();
        $updateConfiguredBundleRequestTransfer->getQuote()->setUuid(static::FAKE_QUOTE_UUID);

        // Act
        $quoteResponseTransfer = $this->tester->getFacade()->updateConfiguredBundleQuantity($updateConfiguredBundleRequestTransfer);

        // Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testUpdateConfiguredBundleQuantityUpdatesConfiguredBundleQuantityInPersistentCartWithoutWritePermissions(): void
    {
        // Arrange
        $updateConfiguredBundleRequestTransfer = $this->tester->buildUpdateConfiguredBundleRequest();

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ConfigurableBundleCartsRestApi\Business\ConfigurableBundleCartsRestApiBusinessFactory $configurableBundleCartsRestApiBusinessFactoryMock */
        $configurableBundleCartsRestApiBusinessFactoryMock = $this->getMockBuilder(ConfigurableBundleCartsRestApiBusinessFactory::class)
            ->onlyMethods(['createQuotePermissionChecker'])
            ->getMock();

        $configurableBundleCartsRestApiBusinessFactoryMock
            ->method('createQuotePermissionChecker')
            ->willReturn($this->getQuotePermissionCheckerMock());

        // Act
        $quoteResponseTransfer = $this->tester->getFacadeMock($configurableBundleCartsRestApiBusinessFactoryMock)
            ->updateConfiguredBundleQuantity($updateConfiguredBundleRequestTransfer);

        // Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::ERROR_IDENTIFIER_UNAUTHORIZED_CART_ACTION,
            $quoteResponseTransfer->getErrors()[0]->getErrorIdentifier()
        );
    }

    /**
     * @return void
     */
    public function testUpdateConfiguredBundleQuantityUpdatesConfiguredBundleQuantityInPersistentCartWithErrorDuringPersistentFacadeCall(): void
    {
        // Arrange
        $updateConfiguredBundleRequestTransfer = $this->tester->buildUpdateConfiguredBundleRequest();

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ConfigurableBundleCartsRestApi\Business\ConfigurableBundleCartsRestApiBusinessFactory $configurableBundleCartsRestApiBusinessFactoryMock */
        $configurableBundleCartsRestApiBusinessFactoryMock = $this->getMockBuilder(ConfigurableBundleCartsRestApiBusinessFactory::class)
            ->onlyMethods(['getPersistentCartFacade'])
            ->getMock();

        $configurableBundleCartsRestApiBusinessFactoryMock
            ->method('getPersistentCartFacade')
            ->willReturn($this->getConfigurableBundleCartsRestApiToPersistentCartFacadeBridgeMock());

        // Act
        $quoteResponseTransfer = $this->tester->getFacadeMock($configurableBundleCartsRestApiBusinessFactoryMock)
            ->updateConfiguredBundleQuantity($updateConfiguredBundleRequestTransfer);

        // Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::ERROR_IDENTIFIER_FAILED_UPDATING_CONFIGURED_BUNDLE,
            $quoteResponseTransfer->getErrors()[0]->getErrorIdentifier()
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ConfigurableBundleCartsRestApi\Business\Checker\QuotePermissionChecker
     */
    protected function getQuotePermissionCheckerMock(): QuotePermissionChecker
    {
        $quotePermissionCheckerMock = $this->getMockBuilder(QuotePermissionChecker::class)
            ->onlyMethods(['checkQuoteWritePermission'])
            ->disableOriginalConstructor()
            ->getMock();

        $quotePermissionCheckerMock
            ->method('checkQuoteWritePermission')
            ->willReturn(false);

        return $quotePermissionCheckerMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ConfigurableBundleCartsRestApi\Dependency\Facade\ConfigurableBundleCartsRestApiToPersistentCartFacadeBridge
     */
    protected function getConfigurableBundleCartsRestApiToPersistentCartFacadeBridgeMock(): ConfigurableBundleCartsRestApiToPersistentCartFacadeBridge
    {
        $configurableBundleCartsRestApiToPersistentCartFacadeBridgeMock = $this->getMockBuilder(ConfigurableBundleCartsRestApiToPersistentCartFacadeBridge::class)
            ->onlyMethods(['updateQuantity'])
            ->disableOriginalConstructor()
            ->getMock();

        $configurableBundleCartsRestApiToPersistentCartFacadeBridgeMock
            ->method('updateQuantity')
            ->willReturn((new QuoteResponseTransfer())->setIsSuccessful(false));

        return $configurableBundleCartsRestApiToPersistentCartFacadeBridgeMock;
    }
}
