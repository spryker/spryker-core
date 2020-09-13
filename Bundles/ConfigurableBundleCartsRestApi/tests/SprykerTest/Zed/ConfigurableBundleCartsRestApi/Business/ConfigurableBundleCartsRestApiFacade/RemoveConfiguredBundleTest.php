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
 * @group RemoveConfiguredBundleTest
 * Add your own group annotations below this line
 */
class RemoveConfiguredBundleTest extends Unit
{
    protected const FAKE_QUOTE_UUID = 'FAKE_QUOTE_UUID';

    /**
     * @uses \Spryker\Shared\CartsRestApi\CartsRestApiConfig::ERROR_IDENTIFIER_UNAUTHORIZED_CART_ACTION
     */
    protected const ERROR_IDENTIFIER_UNAUTHORIZED_CART_ACTION = 'ERROR_IDENTIFIER_UNAUTHORIZED_CART_ACTION';

    /**
     * @uses \Spryker\Shared\ConfigurableBundleCartsRestApi\ConfigurableBundleCartsRestApiConfig::ERROR_IDENTIFIER_FAILED_REMOVING_CONFIGURED_BUNDLE
     */
    protected const ERROR_IDENTIFIER_FAILED_REMOVING_CONFIGURED_BUNDLE = 'ERROR_IDENTIFIER_FAILED_REMOVING_CONFIGURED_BUNDLE';

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
    public function testRemoveConfiguredBundleUpdatesConfiguredBundleQuantity(): void
    {
        // Arrange
        $updateConfiguredBundleRequestTransfer = $this->tester->buildUpdateConfiguredBundleRequest();

        // Act
        $quoteResponseTransfer = $this->tester->getFacade()->removeConfiguredBundle($updateConfiguredBundleRequestTransfer);

        // Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
        $this->assertEmpty($quoteResponseTransfer->getQuoteTransfer()->getItems());
    }

    /**
     * @return void
     */
    public function testRemoveConfiguredBundleThrowsExceptionWithEmptyGroupKeyField(): void
    {
        // Arrange
        $updateConfiguredBundleRequestTransfer = $this->tester->buildUpdateConfiguredBundleRequest();
        $updateConfiguredBundleRequestTransfer->setGroupKey(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->removeConfiguredBundle($updateConfiguredBundleRequestTransfer);
    }

    /**
     * @return void
     */
    public function testRemoveConfiguredBundleThrowsExceptionWithEmptyQuoteField(): void
    {
        // Arrange
        $updateConfiguredBundleRequestTransfer = $this->tester->buildUpdateConfiguredBundleRequest();
        $updateConfiguredBundleRequestTransfer->setQuote(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->removeConfiguredBundle($updateConfiguredBundleRequestTransfer);
    }

    /**
     * @return void
     */
    public function testRemoveConfiguredBundleThrowsExceptionWithEmptyQuoteUuidField(): void
    {
        // Arrange
        $updateConfiguredBundleRequestTransfer = $this->tester->buildUpdateConfiguredBundleRequest();
        $updateConfiguredBundleRequestTransfer->getQuote()->setUuid(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->removeConfiguredBundle($updateConfiguredBundleRequestTransfer);
    }

    /**
     * @return void
     */
    public function testRemoveConfiguredBundleThrowsExceptionWithEmptyCustomerField(): void
    {
        // Arrange
        $updateConfiguredBundleRequestTransfer = $this->tester->buildUpdateConfiguredBundleRequest();
        $updateConfiguredBundleRequestTransfer->getQuote()->setCustomer(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->removeConfiguredBundle($updateConfiguredBundleRequestTransfer);
    }

    /**
     * @return void
     */
    public function testRemoveConfiguredBundleThrowsExceptionWithEmptyQuoteCustomerReferenceField(): void
    {
        // Arrange
        $updateConfiguredBundleRequestTransfer = $this->tester->buildUpdateConfiguredBundleRequest();
        $updateConfiguredBundleRequestTransfer->getQuote()->setCustomerReference(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->removeConfiguredBundle($updateConfiguredBundleRequestTransfer);
    }

    /**
     * @return void
     */
    public function testRemoveConfiguredBundleThrowsExceptionWithEmptyCustomerReferenceField(): void
    {
        // Arrange
        $updateConfiguredBundleRequestTransfer = $this->tester->buildUpdateConfiguredBundleRequest();
        $updateConfiguredBundleRequestTransfer->getQuote()->getCustomer()->setCustomerReference(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->removeConfiguredBundle($updateConfiguredBundleRequestTransfer);
    }

    /**
     * @return void
     */
    public function testRemoveConfiguredBundleRemovesConfiguredBundleFromFakePersistentCart(): void
    {
        // Arrange
        $updateConfiguredBundleRequestTransfer = $this->tester->buildUpdateConfiguredBundleRequest();
        $updateConfiguredBundleRequestTransfer->getQuote()->setUuid(static::FAKE_QUOTE_UUID);

        // Act
        $quoteResponseTransfer = $this->tester->getFacade()->removeConfiguredBundle($updateConfiguredBundleRequestTransfer);

        // Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testRemoveConfiguredBundleRemovesConfiguredBundleFromPersistentCartWithoutWritePermissions(): void
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
            ->removeConfiguredBundle($updateConfiguredBundleRequestTransfer);

        // Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::ERROR_IDENTIFIER_UNAUTHORIZED_CART_ACTION,
            $quoteResponseTransfer->getErrors()[0]->getErrorIdentifier()
        );
    }

    /**
     * @return void
     */
    public function testRemoveConfiguredBundleRemovesConfiguredBundleFromPersistentCartWithErrorDuringPersistentFacadeCall(): void
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
            ->removeConfiguredBundle($updateConfiguredBundleRequestTransfer);

        // Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::ERROR_IDENTIFIER_FAILED_REMOVING_CONFIGURED_BUNDLE,
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
            ->onlyMethods(['remove'])
            ->disableOriginalConstructor()
            ->getMock();

        $configurableBundleCartsRestApiToPersistentCartFacadeBridgeMock
            ->method('remove')
            ->willReturn((new QuoteResponseTransfer())->setIsSuccessful(false));

        return $configurableBundleCartsRestApiToPersistentCartFacadeBridgeMock;
    }
}
