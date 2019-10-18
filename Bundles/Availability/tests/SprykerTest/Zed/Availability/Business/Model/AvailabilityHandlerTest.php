<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Availability\Business\Model;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\StoreBuilder;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\Availability\Business\Model\AvailabilityHandler;
use Spryker\Zed\Availability\Business\Model\ProductAvailabilityCalculatorInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToEventFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsFacadeInterface;
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
    public const PRODUCT_ABSTRACT_SKU = 'sku-123';
    public const PRODUCT_SKU = 'sku-123-321';

    protected const STORE_NAME = 'DE';

    /**
     * @var \SprykerTest\Zed\Availability\AvailabilityBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testUpdateAvailabilityShouldTouchWhenStockUpdated()
    {
        $productTransfer = $this->tester->haveProduct();
        $availabilityCalculatorMock = $this->createAvailabilityCalculatorMock();
        $availabilityCalculatorMock->method('calculateAvailabilityForProductConcrete')->willReturn(new Decimal(15));

        $availabilityRepositoryMock = $this->createAvailabilityRepositoryMock();
        $availabilityRepositoryMock->method('getAbstractSkuFromProductConcrete')
            ->willReturn($productTransfer->getAbstractSku());
        $availabilityRepositoryMock->method('findIdProductAbstractAvailabilityBySku')
            ->willReturn($productTransfer->getFkProductAbstract());

        $availabilityEntityManagerMock = $this->createAvailabilityEntityManagerMock();
        $availabilityEntityManagerMock->method('saveProductConcreteAvailability')
            ->willReturn(true);

        $stockFacadeMock = $this->createAvailabilityToStockFacadeMock();
        $stockFacadeMock->method('getStoresWhereProductStockIsDefined')
            ->willReturn([$this->createStoreTransfer()]);

        $touchFacadeMock = $this->createTouchFacadeMock();
        $touchFacadeMock->expects($this->once())->method('touchActive');

        $availabilityHandler = $this->createAvailabilityHandler(
            $availabilityRepositoryMock,
            $availabilityEntityManagerMock,
            $availabilityCalculatorMock,
            $touchFacadeMock,
            null,
            $stockFacadeMock
        );

        $availabilityHandler->updateAvailability($productTransfer->getSku());
    }

    /**
     * @return void
     */
    public function testUpdateAvailabilityShouldTouchAndUpdate()
    {
        $availabilityCalculatorMock = $this->createAvailabilityCalculatorMock();
        $availabilityCalculatorMock->method('calculateAvailabilityForProductConcrete')->willReturn(new Decimal(5));

        $availabilityRepositoryMock = $this->createAvailabilityRepositoryMock();
        $availabilityRepositoryMock->method('getAbstractSkuFromProductConcrete')
            ->willReturn(static::PRODUCT_ABSTRACT_SKU);
        $availabilityRepositoryMock->method('findIdProductAbstractAvailabilityBySku')
            ->willReturn(123);

        $availabilityEntityManagerMock = $this->createAvailabilityEntityManagerMock();
        $availabilityEntityManagerMock->method('saveProductConcreteAvailability')
            ->willReturn(true);

        $stockFacadeMock = $this->createAvailabilityToStockFacadeMock();
        $stockFacadeMock->method('getStoresWhereProductStockIsDefined')
            ->willReturn([$this->createStoreTransfer()]);

        $touchFacadeMock = $this->createTouchFacadeMock();
        $touchFacadeMock->expects($this->once())->method('touchActive');

        $availabilityHandler = $this->createAvailabilityHandler(
            $availabilityRepositoryMock,
            $availabilityEntityManagerMock,
            $availabilityCalculatorMock,
            $touchFacadeMock,
            null,
            $stockFacadeMock
        );

        $availabilityHandler->updateAvailability(self::PRODUCT_SKU);
    }

    /**
     * @param \Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface $availabilityRepositoryMock
     * @param \Spryker\Zed\Availability\Persistence\AvailabilityEntityManagerInterface $availabilityEntityManagerMock
     * @param \Spryker\Zed\Availability\Business\Model\ProductAvailabilityCalculatorInterface $availabilityCalculatorMock
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchFacadeInterface $touchFacade
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface|null $availabilityToStoreFacade
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockFacadeInterface|null $availabilityToStockFacade
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToEventFacadeInterface|null $availabilityToEventFacade
     *
     * @return \Spryker\Zed\Availability\Business\Model\AvailabilityHandler
     */
    protected function createAvailabilityHandler(
        AvailabilityRepositoryInterface $availabilityRepositoryMock,
        AvailabilityEntityManagerInterface $availabilityEntityManagerMock,
        ProductAvailabilityCalculatorInterface $availabilityCalculatorMock,
        AvailabilityToTouchFacadeInterface $touchFacade,
        ?AvailabilityToStoreFacadeInterface $availabilityToStoreFacade = null,
        ?AvailabilityToStockFacadeInterface $availabilityToStockFacade = null,
        ?AvailabilityToEventFacadeInterface $availabilityToEventFacade = null
    ) {
        if ($availabilityToStoreFacade === null) {
            $availabilityToStoreFacade = $this->createStoreFacade();
            $availabilityToStoreFacade->method('getCurrentStore')
                ->willReturn(new StoreTransfer());
        }

        if ($availabilityToStockFacade === null) {
            $availabilityToStockFacade = $this->createAvailabilityToStockFacadeMock();
        }

        if ($availabilityToEventFacade === null) {
            $availabilityToEventFacade = $this->createAvailabilityToEventFacade();
        }

        return new AvailabilityHandler(
            $availabilityRepositoryMock,
            $availabilityEntityManagerMock,
            $availabilityCalculatorMock,
            $touchFacade,
            $availabilityToStoreFacade,
            $availabilityToStockFacade,
            $availabilityToEventFacade
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsFacadeInterface
     */
    protected function createOmsFacadeMock()
    {
        return $this->getMockBuilder(AvailabilityToOmsFacadeInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchFacadeInterface
     */
    protected function createTouchFacadeMock()
    {
        return $this->getMockBuilder(AvailabilityToTouchFacadeInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Availability\Business\Model\ProductAvailabilityCalculatorInterface
     */
    protected function createAvailabilityCalculatorMock()
    {
        return $this->getMockBuilder(ProductAvailabilityCalculatorInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface
     */
    protected function createAvailabilityRepositoryMock()
    {
        return $this->getMockBuilder(AvailabilityRepositoryInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Availability\Persistence\AvailabilityEntityManagerInterface
     */
    protected function createAvailabilityEntityManagerMock()
    {
        return $this->getMockBuilder(AvailabilityEntityManagerInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface
     */
    protected function createStoreFacade()
    {
        return $this->getMockBuilder(AvailabilityToStoreFacadeInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Availability\Dependency\Facade\AvailabilityToEventFacadeInterface
     */
    protected function createAvailabilityToEventFacade()
    {
        return $this->getMockBuilder(AvailabilityToEventFacadeInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockFacadeInterface
     */
    protected function createAvailabilityToStockFacadeMock()
    {
        return $this->getMockBuilder(AvailabilityToStockFacadeInterface::class)
            ->getMock();
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function createStoreTransfer(): StoreTransfer
    {
        return (new StoreBuilder([
            StoreTransfer::NAME => static::STORE_NAME,
        ]))->build();
    }
}
