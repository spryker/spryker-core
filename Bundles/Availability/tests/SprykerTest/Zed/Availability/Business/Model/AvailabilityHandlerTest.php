<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Availability\Business\Model;

use Orm\Zed\Availability\Persistence\SpyAvailability;
use Orm\Zed\Availability\Persistence\SpyAvailabilityAbstract;
use Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery;
use Orm\Zed\Availability\Persistence\SpyAvailabilityQuery;
use PHPUnit_Framework_TestCase;
use Spryker\Zed\Availability\Business\Model\AvailabilityHandler;
use Spryker\Zed\Availability\Business\Model\SellableInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToProductInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchInterface;
use Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Availability
 * @group Business
 * @group Model
 * @group AvailabilityHandlerTest
 * Add your own group annotations below this line
 */
class AvailabilityHandlerTest extends PHPUnit_Framework_TestCase
{

    const PRODUCT_SKU = 'sku-123-321';

    /**
     * @return void
     */
    public function testUpdateAvailabilityShouldTouchWhenStockUpdated()
    {
        $availabilityContainerMock = $this->createAvailabilityQueryContainerMock(0);

        $sellableMock = $this->createSellableMock();
        $sellableMock->method('calculateStockForProduct')->willReturn(15);

        $touchFacadeMock = $this->createTouchFacadeMock();
        $touchFacadeMock->expects($this->once())->method('touchActive');

        $availabilityHandler = $this->createAvailabilityHandler(
            $sellableMock,
            null,
            $touchFacadeMock,
            $availabilityContainerMock
        );

        $availabilityHandler->updateAvailability(self::PRODUCT_SKU);
    }

    /**
     * @return void
     */
    public function testUpdateAvailabilityShouldTouchAndUpdateNewStock()
    {
        $availabilityContainerMock = $this->createAvailabilityQueryContainerMock(5);

        $sellableMock = $this->createSellableMock();
        $sellableMock->method('calculateStockForProduct')->willReturn(0);

        $touchFacadeMock = $this->createTouchFacadeMock();
        $touchFacadeMock->expects($this->once())->method('touchActive');

        $availabilityHandler = $this->createAvailabilityHandler(
            $sellableMock,
            null,
            $touchFacadeMock,
            $availabilityContainerMock
        );

        $availabilityHandler->updateAvailability(self::PRODUCT_SKU);
    }

    /**
     * @param \Spryker\Zed\Availability\Business\Model\SellableInterface|null $sellable
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface|null $stockFacade
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchInterface|null $touchFacade
     * @param \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface|null $availabilityQueryContainer
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToProductInterface|null $availabilityToProductFacade
     *
     * @return \Spryker\Zed\Availability\Business\Model\AvailabilityHandler
     */
    protected function createAvailabilityHandler(
        SellableInterface $sellable = null,
        AvailabilityToStockInterface $stockFacade = null,
        AvailabilityToTouchInterface $touchFacade = null,
        AvailabilityQueryContainerInterface $availabilityQueryContainer = null,
        AvailabilityToProductInterface $availabilityToProductFacade = null
    ) {

        if ($sellable === null) {
            $sellable = $this->createSellableMock();
        }

        if ($stockFacade === null) {
            $stockFacade = $this->createStockFacadeMock();
        }

        if ($touchFacade === null) {
            $touchFacade = $this->createTouchFacadeMock();
        }

        if ($availabilityQueryContainer === null) {
            $availabilityQueryContainer = $this->createAvailabilityQueryContainerMock();
        }

        if ($availabilityToProductFacade === null) {
            $availabilityToProductFacade = $this->createAvailabilityToProductFacade();
        }

        return new AvailabilityHandler(
            $sellable,
            $stockFacade,
            $touchFacade,
            $availabilityQueryContainer,
            $availabilityToProductFacade
        );
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface
     */
    protected function createStockFacadeMock()
    {
        return $this->getMockBuilder(AvailabilityToStockInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsInterface
     */
    protected function createOmsFacadeMock()
    {
        return $this->getMockBuilder(AvailabilityToOmsInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchInterface
     */
    protected function createTouchFacadeMock()
    {
        return $this->getMockBuilder(AvailabilityToTouchInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Availability\Business\Model\SellableInterface
     */
    protected function createSellableMock()
    {
        return $this->getMockBuilder(SellableInterface::class)
            ->getMock();
    }

    /**
     * @param int $availabilityQuantity
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface
     */
    protected function createAvailabilityQueryContainerMock($availabilityQuantity = 0)
    {
        $availabilityContainerMock = $this->getMockBuilder(AvailabilityQueryContainerInterface::class)
            ->getMock();

        $availabilitQueryMock = $this->getMockBuilder(SpyAvailabilityQuery::class)->getMock();
        $availabilitAbstractQueryMock = $this->getMockBuilder(SpyAvailabilityAbstractQuery::class)->getMock();

        $availabilityEntity = $this->createAvailabilityEntityMock();
        $availabilityEntity->method('getQuantity')
            ->willReturn($availabilityQuantity);

        $availabilitQueryMock->method('findOne')
            ->willReturn($availabilityEntity);

        $availabilityEntity = $this->createAvailabilityEntityMock();
        $availabilitQueryMock->method('findOneOrCreate')
            ->willReturn($availabilityEntity);

        $availabilityEntity = $this->createAvailabilityEntityMock();
        $availabilitQueryMock->method('findOneOrCreate')
            ->willReturn($availabilityEntity);

        $availabilityContainerMock->method('querySpyAvailabilityBySku')
            ->willReturn($availabilitQueryMock);

        $availabilityAbstractEntityMock = $this->createAvailabilityAbstractEntityMock();
        $availabilitAbstractQueryMock->method('findOne')->willReturn($availabilityAbstractEntityMock);

        $availabilityContainerMock->method('queryAvailabilityAbstractByIdAvailabilityAbstract')
            ->willReturn($availabilitAbstractQueryMock);

        $availabilityContainerMock->method('querySumQuantityOfAvailabilityAbstract')
            ->willReturn($availabilityEntity);

        return $availabilityContainerMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\Availability\Persistence\SpyAvailability
     */
    protected function createAvailabilityEntityMock()
    {
        return $this->getMockBuilder(SpyAvailability::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\Availability\Persistence\SpyAvailabilityAbstract
     */
    protected function createAvailabilityAbstractEntityMock()
    {
        return $this->getMockBuilder(SpyAvailabilityAbstract::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Availability\Dependency\Facade\AvailabilityToProductInterface
     */
    protected function createAvailabilityToProductFacade()
    {
        return $this->getMockBuilder(AvailabilityToProductInterface::class)
            ->getMock();
    }

}
