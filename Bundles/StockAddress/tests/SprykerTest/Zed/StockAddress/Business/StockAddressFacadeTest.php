<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StockAddress\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\StockAddressBuilder;
use Generated\Shared\DataBuilder\StockBuilder;
use Generated\Shared\Transfer\StockAddressTransfer;
use Generated\Shared\Transfer\StockCollectionTransfer;
use Generated\Shared\Transfer\StockResponseTransfer;
use Orm\Zed\Stock\Persistence\SpyStockQuery;
use Orm\Zed\StockAddress\Persistence\SpyStockAddressQuery;
use Spryker\Zed\Stock\Business\Exception\StockNotSavedException;
use Spryker\Zed\Stock\Business\StockFacadeInterface;
use Spryker\Zed\Stock\StockDependencyProvider;
use Spryker\Zed\StockAddress\Business\StockAddressFacadeInterface;
use Spryker\Zed\StockAddress\Communication\Plugin\Stock\StockAddressStockPostCreatePlugin;
use Spryker\Zed\StockAddress\Communication\Plugin\Stock\StockAddressStockPostUpdatePlugin;
use Spryker\Zed\StockExtension\Dependency\Plugin\StockPostCreatePluginInterface;
use Spryker\Zed\StockExtension\Dependency\Plugin\StockPostUpdatePluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group StockAddress
 * @group Business
 * @group Facade
 * @group StockAddressFacadeTest
 * Add your own group annotations below this line
 */
class StockAddressFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\StockAddress\StockAddressBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandStockCollectionWillExpandStockTransferWithStockAddressTransfer(): void
    {
        // Arrange
        $countryTransfer = $this->tester->haveCountry();
        $stockTransfer = $this->tester->haveStock();
        $stockAddressTransfer = $this->tester->haveStockAddress([
            StockAddressTransfer::ID_STOCK => $stockTransfer->getIdStockOrFail(),
            StockAddressTransfer::COUNTRY => $countryTransfer->toArray(),
        ]);

        $stockCollectionTransfer = (new StockCollectionTransfer())->addStock($stockTransfer);

        // Act
        $stockCollectionTransfer = $this->getStockAddressFacade()->expandStockCollection($stockCollectionTransfer);

        // Assert
        $this->assertCount(1, $stockCollectionTransfer->getStocks());

        /** @var \Generated\Shared\Transfer\StockTransfer $stockTransfer */
        $stockTransfer = $stockCollectionTransfer->getStocks()->offsetGet(0);
        $this->assertNotNull($stockTransfer->getAddress());
        $this->assertEqualsCanonicalizing($stockAddressTransfer->toArray(), $stockTransfer->getAddress()->toArray());
    }

    /**
     * @return void
     */
    public function testExpandStockCollectionWillNotExpandStockTransferWithNoStockAddressRelated(): void
    {
        // Arrange
        $stockTransfer = $this->tester->haveStock();
        $stockCollectionTransfer = (new StockCollectionTransfer())->addStock($stockTransfer);

        // Act
        $stockCollectionTransfer = $this->getStockAddressFacade()->expandStockCollection($stockCollectionTransfer);

        // Assert
        $this->assertCount(1, $stockCollectionTransfer->getStocks());

        /** @var \Generated\Shared\Transfer\StockTransfer $stockTransfer */
        $stockTransfer = $stockCollectionTransfer->getStocks()->offsetGet(0);
        $this->assertNull($stockTransfer->getAddress());
    }

    /**
     * @return void
     */
    public function testCreateStockAddressForStockWillCreateStockAddressIfItIsProvided(): void
    {
        // Arrange
        $countryTransfer = $this->tester->haveCountry();
        $stockTransfer = $this->tester->haveStock();
        $stockAddressTransfer = (new StockAddressBuilder([
            StockAddressTransfer::ID_STOCK => $stockTransfer->getIdStockOrFail(),
            StockAddressTransfer::COUNTRY => $countryTransfer->toArray(),
        ]))->build();

        $stockTransfer->setAddress($stockAddressTransfer);

        // Act
        $stockResponseTransfer = $this->getStockAddressFacade()->createStockAddressForStock($stockTransfer);

        // Assert
        $this->assertTrue($stockResponseTransfer->getIsSuccessful());
        $this->assertNotNull($stockResponseTransfer->getStock()->getAddress());

        $stockAddressCount = SpyStockAddressQuery::create()
            ->filterByFkStock($stockTransfer->getIdStockOrFail())
            ->count();
        $this->assertEquals(1, $stockAddressCount);
    }

    /**
     * @return void
     */
    public function testCreateStockAddressForStockWillDoNothingIfStockAddressIsNotProvided(): void
    {
        // Arrange
        $stockTransfer = $this->tester->haveStock();

        // Act
        $stockResponseTransfer = $this->getStockAddressFacade()->createStockAddressForStock($stockTransfer);

        // Assert
        $this->assertTrue($stockResponseTransfer->getIsSuccessful());
        $this->assertNull($stockResponseTransfer->getStock()->getAddress());

        $stockAddressCount = SpyStockAddressQuery::create()
            ->filterByFkStock($stockTransfer->getIdStockOrFail())
            ->count();
        $this->assertEquals(0, $stockAddressCount);
    }

    /**
     * @return void
     */
    public function testUpdateStockAddressForStockWillUpdateStockAddress(): void
    {
        // Arrange
        $countryTransfer = $this->tester->haveCountry();
        $stockTransfer = $this->tester->haveStock();
        $this->tester->haveStockAddress([
            StockAddressTransfer::ID_STOCK => $stockTransfer->getIdStockOrFail(),
            StockAddressTransfer::COUNTRY => $countryTransfer->toArray(),
        ]);

        $newStockAddressTransfer = (new StockAddressBuilder([
            StockAddressTransfer::ID_STOCK => $stockTransfer->getIdStockOrFail(),
            StockAddressTransfer::COUNTRY => $countryTransfer->toArray(),
        ]))->build();
        $stockTransfer->setAddress($newStockAddressTransfer);

        // Act
        $stockResponseTransfer = $this->getStockAddressFacade()->updateStockAddressForStock($stockTransfer);

        // Assert
        $this->assertTrue($stockResponseTransfer->getIsSuccessful());
        $this->assertNotNull($stockResponseTransfer->getStock()->getAddress());
        $this->assertEqualsCanonicalizing($stockResponseTransfer->getStock()->getAddress()->toArray(), $newStockAddressTransfer->toArray());
    }

    /**
     * @return void
     */
    public function testUpdateStockAddressForStockWillDoNothingIfStockAddressIsNotProvided(): void
    {
        // Arrange
        $stockTransfer = $this->tester->haveStock();

        // Act
        $this->getStockAddressFacade()->updateStockAddressForStock($stockTransfer);

        // Assert
        $stockAddressCount = SpyStockAddressQuery::create()
            ->filterByFkStock($stockTransfer->getIdStockOrFail())
            ->count();
        $this->assertEquals(0, $stockAddressCount);
    }

    /**
     * @return void
     */
    public function testUpdateStockAddressForStockWillRemoveExistingStockAddressIfStockAddressIsNotProvided(): void
    {
        // Arrange
        $countryTransfer = $this->tester->haveCountry();
        $stockTransfer = $this->tester->haveStock();
        $this->tester->haveStockAddress([
            StockAddressTransfer::ID_STOCK => $stockTransfer->getIdStockOrFail(),
            StockAddressTransfer::COUNTRY => $countryTransfer->toArray(),
        ]);

        // Act
        $this->getStockAddressFacade()->updateStockAddressForStock($stockTransfer);

        // Assert
        $stockAddressCount = SpyStockAddressQuery::create()
            ->filterByFkStock($stockTransfer->getIdStockOrFail())
            ->count();
        $this->assertEquals(0, $stockAddressCount);
    }

    /**
     * @return void
     */
    public function testCreateStockWillCreateStockAndStockAddress(): void
    {
        // Arrange
        $this->tester->setDependency(StockDependencyProvider::PLUGINS_STOCK_POST_CREATE, [
            new StockAddressStockPostCreatePlugin(),
        ]);
        $countryTransfer = $this->tester->haveCountry();
        $stockTransfer = (new StockBuilder())
            ->withAddress([StockAddressTransfer::COUNTRY => $countryTransfer->toArray()])
            ->build();

        // Act
        $stockResponseTransfer = $this->getStockFacade()->createStock($stockTransfer);

        // Assert
        $this->assertTrue($stockResponseTransfer->getIsSuccessful());

        $stockEntity = SpyStockQuery::create()
            ->filterByName($stockTransfer->getNameOrFail())
            ->findOne();
        $this->assertNotNull($stockEntity);

        $stockAddressEntity = SpyStockAddressQuery::create()
            ->filterByFkStock($stockEntity->getIdStock())
            ->findOne();
        $this->assertNotNull($stockAddressEntity);
    }

    /**
     * @return void
     */
    public function testUpdateStockWillCreateStockAddress(): void
    {
        // Arrange
        $this->tester->setDependency(StockDependencyProvider::PLUGINS_STOCK_POST_UPDATE, [
            new StockAddressStockPostUpdatePlugin(),
        ]);
        $countryTransfer = $this->tester->haveCountry();
        $stockTransfer = $this->tester->haveStock();
        $stockAddressTransfer = (new StockAddressBuilder([
            StockAddressTransfer::COUNTRY => $countryTransfer->toArray(),
        ]))->build();

        $stockTransfer->setAddress($stockAddressTransfer);

        // Act
        $stockResponseTransfer = $this->getStockFacade()->updateStock($stockTransfer);

        // Assert
        $this->assertTrue($stockResponseTransfer->getIsSuccessful());

        $stockAddressEntity = SpyStockAddressQuery::create()
            ->filterByFkStock($stockTransfer->getIdStock())
            ->findOne();
        $this->assertNotNull($stockAddressEntity);
    }

    /**
     * @return void
     */
    public function testCreateStockWillThrowAnExceptionIfStockAddressWasNotCreated(): void
    {
        // Arrange
        $this->tester->setDependency(StockDependencyProvider::PLUGINS_STOCK_POST_CREATE, [
            $this->createStockPostCreatePluginMock(),
        ]);
        $countryTransfer = $this->tester->haveCountry();
        $stockTransfer = (new StockBuilder())
            ->withAddress([StockAddressTransfer::COUNTRY => $countryTransfer->toArray()])
            ->build();

        $this->expectException(StockNotSavedException::class);

        // Act
        $this->getStockFacade()->createStock($stockTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateStockWillThrowAnExceptionIfStockAddressWasNotCreated(): void
    {
        // Arrange
        $this->tester->setDependency(StockDependencyProvider::PLUGINS_STOCK_POST_UPDATE, [
            $this->createStockPostUpdatePluginMock(),
        ]);
        $countryTransfer = $this->tester->haveCountry();
        $stockTransfer = $this->tester->haveStock();
        $stockAddressTransfer = (new StockAddressBuilder([
            StockAddressTransfer::COUNTRY => $countryTransfer->toArray(),
        ]))->build();

        $stockTransfer->setAddress($stockAddressTransfer);

        $this->expectException(StockNotSavedException::class);

        // Act
        $this->getStockFacade()->updateStock($stockTransfer);
    }

    /**
     * @return \Spryker\Zed\StockAddress\Business\StockAddressFacadeInterface
     */
    protected function getStockAddressFacade(): StockAddressFacadeInterface
    {
        return $this->tester->getLocator()->stockAddress()->facade();
    }

    /**
     * @return \Spryker\Zed\Stock\Business\StockFacadeInterface
     */
    protected function getStockFacade(): StockFacadeInterface
    {
        return $this->tester->getLocator()->stock()->facade();
    }

    /**
     * @return \Spryker\Zed\StockExtension\Dependency\Plugin\StockPostCreatePluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createStockPostCreatePluginMock(): StockPostCreatePluginInterface
    {
        $stockPostCreatePluginMock = $this->getMockBuilder(StockPostCreatePluginInterface::class)->getMock();
        $stockPostCreatePluginMock
            ->method('postCreate')
            ->willReturn((new StockResponseTransfer())->setIsSuccessful(false));

        return $stockPostCreatePluginMock;
    }

    /**
     * @return \Spryker\Zed\StockExtension\Dependency\Plugin\StockPostUpdatePluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createStockPostUpdatePluginMock(): StockPostUpdatePluginInterface
    {
        $stockPostUpdatePluginMock = $this->getMockBuilder(StockPostUpdatePluginInterface::class)->getMock();
        $stockPostUpdatePluginMock
            ->method('postUpdate')
            ->willReturn((new StockResponseTransfer())->setIsSuccessful(false));

        return $stockPostUpdatePluginMock;
    }
}
