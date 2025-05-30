<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Availability\Business\Model;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\Availability\Business\Model\AvailabilityHandler;
use Spryker\Zed\Availability\Business\Model\AvailabilityHandlerInterface;
use Spryker\Zed\Availability\Business\Model\ProductAvailabilityCalculatorInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToEventFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToProductFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchFacadeInterface;
use Spryker\Zed\Availability\Persistence\AvailabilityEntityManagerInterface;
use Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Availability
 * @group Business
 * @group Model
 * @group AvailabilityHandlerTest
 * Add your own group annotations below this line
 */
class AvailabilityHandlerTest extends Unit
{
    /**
     * @var string
     */
    public const PRODUCT_ABSTRACT_SKU = 'sku-123';

    /**
     * @var string
     */
    public const PRODUCT_SKU = 'sku-123-321';

    /**
     * @var string
     */
    protected const STORE_NAME = 'DE';

    /**
     * @var \SprykerTest\Zed\Availability\AvailabilityBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        $this->tester->clearAvailabilityHandlerCache();
    }

    /**
     * @return void
     */
    public function testUpdateAvailabilityShouldTouchWhenStockUpdated(): void
    {
        $productTransfer = $this->tester->haveProduct();
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME]);
        $availabilityCalculatorMock = $this->createAvailabilityCalculatorMock();
        $availabilityCalculatorMock->method('calculateAvailabilityForProductConcrete')->willReturn(new Decimal(15));
        $availabilityCalculatorMock->method('getCalculatedProductAbstractAvailabilityTransfer')
            ->willReturn((new ProductAbstractAvailabilityTransfer())->setAvailability(10));

        $availabilityRepositoryMock = $this->createAvailabilityRepositoryMock();
        $availabilityRepositoryMock->method('getAbstractSkuFromProductConcrete')
            ->willReturn($productTransfer->getAbstractSku());
        $availabilityRepositoryMock->method('findIdProductAbstractAvailabilityBySku')
            ->willReturn($productTransfer->getFkProductAbstract());
        $availabilityRepositoryMock->method('getProductConcreteWithAvailability')
            ->willReturn($this->tester->prepareProductAvailabilityDataTransfer($productTransfer, $storeTransfer));

        $availabilityEntityManagerMock = $this->createAvailabilityEntityManagerMock();
        $availabilityEntityManagerMock->method('saveProductConcreteAvailability')
            ->willReturn(true);

        $stockFacadeMock = $this->createAvailabilityToStockFacadeMock();
        $stockFacadeMock->method('getStoresWhereProductStockIsDefined')
            ->willReturn([$storeTransfer]);

        $touchFacadeMock = $this->createTouchFacadeMock();
        $touchFacadeMock->method('isTouchEnabled')
            ->willReturn(true);
        $touchFacadeMock->expects($this->once())->method('touchActive');

        $availabilityHandler = $this->createAvailabilityHandler(
            $availabilityRepositoryMock,
            $availabilityEntityManagerMock,
            $availabilityCalculatorMock,
            $touchFacadeMock,
            $stockFacadeMock,
        );

        $availabilityHandler->updateAvailability($productTransfer->getSku());
    }

    /**
     * @return void
     */
    public function testUpdateAvailabilityShouldTouchAndUpdate(): void
    {
        $productConcreteTransfer = $this->tester->haveProduct([ProductConcreteTransfer::SKU => static::PRODUCT_ABSTRACT_SKU]);
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME]);
        $availabilityCalculatorMock = $this->createAvailabilityCalculatorMock();
        $availabilityCalculatorMock->method('calculateAvailabilityForProductConcrete')->willReturn(new Decimal(5));
        $availabilityCalculatorMock->method('getCalculatedProductAbstractAvailabilityTransfer')
            ->willReturn((new ProductAbstractAvailabilityTransfer())->setAvailability(10));

        $availabilityRepositoryMock = $this->createAvailabilityRepositoryMock();
        $availabilityRepositoryMock->method('getAbstractSkuFromProductConcrete')
            ->willReturn(static::PRODUCT_ABSTRACT_SKU);
        $availabilityRepositoryMock->method('findIdProductAbstractAvailabilityBySku')
            ->willReturn(123);
        $availabilityRepositoryMock->method('getProductConcreteWithAvailability')
            ->willReturn($this->tester->prepareProductAvailabilityDataTransfer($productConcreteTransfer, $storeTransfer));

        $availabilityEntityManagerMock = $this->createAvailabilityEntityManagerMock();
        $availabilityEntityManagerMock->method('saveProductConcreteAvailability')
            ->willReturn(true);

        $stockFacadeMock = $this->createAvailabilityToStockFacadeMock();
        $stockFacadeMock->method('getStoresWhereProductStockIsDefined')
            ->willReturn([$this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME])]);

        $touchFacadeMock = $this->createTouchFacadeMock();
        $touchFacadeMock->method('isTouchEnabled')
            ->willReturn(true);
        $touchFacadeMock->expects($this->once())->method('touchActive');

        $availabilityHandler = $this->createAvailabilityHandler(
            $availabilityRepositoryMock,
            $availabilityEntityManagerMock,
            $availabilityCalculatorMock,
            $touchFacadeMock,
            $stockFacadeMock,
        );

        $availabilityHandler->updateAvailability(static::PRODUCT_SKU);
    }

    /**
     * @param \Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface $availabilityRepositoryMock
     * @param \Spryker\Zed\Availability\Persistence\AvailabilityEntityManagerInterface $availabilityEntityManagerMock
     * @param \Spryker\Zed\Availability\Business\Model\ProductAvailabilityCalculatorInterface $availabilityCalculatorMock
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchFacadeInterface $touchFacade
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockFacadeInterface|null $availabilityToStockFacade
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToEventFacadeInterface|null $availabilityToEventFacade
     *
     * @return \Spryker\Zed\Availability\Business\Model\AvailabilityHandlerInterface
     */
    protected function createAvailabilityHandler(
        AvailabilityRepositoryInterface $availabilityRepositoryMock,
        AvailabilityEntityManagerInterface $availabilityEntityManagerMock,
        ProductAvailabilityCalculatorInterface $availabilityCalculatorMock,
        AvailabilityToTouchFacadeInterface $touchFacade,
        ?AvailabilityToStockFacadeInterface $availabilityToStockFacade = null,
        ?AvailabilityToEventFacadeInterface $availabilityToEventFacade = null
    ): AvailabilityHandlerInterface {
        if ($availabilityToStockFacade === null) {
            $availabilityToStockFacade = $this->createAvailabilityToStockFacadeMock();
        }

        if ($availabilityToEventFacade === null) {
            $availabilityToEventFacade = $this->createAvailabilityToEventFacade();
        }

        $storeFacadeMock = $this->createAvailabilityToStoreFacadeInterface();
        $storeFacadeMock->method('getAllStores')
            ->willReturn([$this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME])]);

        return new AvailabilityHandler(
            $availabilityRepositoryMock,
            $availabilityEntityManagerMock,
            $availabilityCalculatorMock,
            $touchFacade,
            $availabilityToStockFacade,
            $availabilityToEventFacade,
            $this->getProductFacadeMock(),
            $storeFacadeMock,
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsFacadeInterface
     */
    protected function createOmsFacadeMock(): AvailabilityToOmsFacadeInterface
    {
        return $this->getMockBuilder(AvailabilityToOmsFacadeInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchFacadeInterface
     */
    protected function createTouchFacadeMock(): AvailabilityToTouchFacadeInterface
    {
        return $this->getMockBuilder(AvailabilityToTouchFacadeInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Availability\Business\Model\ProductAvailabilityCalculatorInterface
     */
    protected function createAvailabilityCalculatorMock(): ProductAvailabilityCalculatorInterface
    {
        return $this->getMockBuilder(ProductAvailabilityCalculatorInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface
     */
    protected function createAvailabilityRepositoryMock(): AvailabilityRepositoryInterface
    {
        return $this->getMockBuilder(AvailabilityRepositoryInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Availability\Persistence\AvailabilityEntityManagerInterface
     */
    protected function createAvailabilityEntityManagerMock(): AvailabilityEntityManagerInterface
    {
        return $this->getMockBuilder(AvailabilityEntityManagerInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Availability\Dependency\Facade\AvailabilityToEventFacadeInterface
     */
    protected function createAvailabilityToEventFacade(): AvailabilityToEventFacadeInterface
    {
        return $this->getMockBuilder(AvailabilityToEventFacadeInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Availability\Dependency\Facade\AvailabilityToProductFacadeInterface
     */
    protected function getProductFacadeMock(): AvailabilityToProductFacadeInterface
    {
        return $this->getMockBuilder(AvailabilityToProductFacadeInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockFacadeInterface
     */
    protected function createAvailabilityToStockFacadeMock(): AvailabilityToStockFacadeInterface
    {
        return $this->getMockBuilder(AvailabilityToStockFacadeInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface
     */
    protected function createAvailabilityToStoreFacadeInterface(): AvailabilityToStoreFacadeInterface
    {
        return $this->getMockBuilder(AvailabilityToStoreFacadeInterface::class)
            ->getMock();
    }
}
