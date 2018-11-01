<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\Persistence;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DiscountCalculatorTransfer;
use Generated\Shared\Transfer\DiscountConditionTransfer;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountGeneralTransfer;
use Generated\Shared\Transfer\DiscountVoucherTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscount;
use Orm\Zed\Discount\Persistence\SpyDiscountQuery;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Zed\Discount\Business\Exception\PersistenceException;
use Spryker\Zed\Discount\Business\Persistence\DiscountPersist;
use Spryker\Zed\Discount\Business\Persistence\DiscountStoreRelationWriter;
use Spryker\Zed\Discount\Business\Voucher\VoucherEngineInterface;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group Persistence
 * @group DiscountPersistTest
 * Add your own group annotations below this line
 */
class DiscountPersistTest extends Unit
{
    /**
     * @return void
     */
    public function testSaveDiscountWithVoucherShouldSaveEntityWithVoucherPool()
    {
        $discountPersist = $this->createDiscountPersist();

        $discountEntityMock = $this->createDiscountEntityMock();
        $discountPersist->method('createDiscountEntity')->willReturn($discountEntityMock);

        $discountVoucherPoolEntity = $this->createVoucherPoolEntity();
        $discountPersist->method('createVoucherPoolEntity')->willReturn($discountVoucherPoolEntity);

        $discountConfiguratorTransfer = $this->createDiscountConfiguratorTransfer();

        $discountPersist->save($discountConfiguratorTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateWhenDiscountExistShouldCallSaveOnDiscountEntity()
    {
        $discountEntityMock = $this->createDiscountEntityMock();
        $voucherPoolEntityMock = $this->createVoucherPoolEntity();

        $discountEntityMock->expects($this->exactly(1))
            ->method('getVoucherPool')
            ->willReturn($voucherPoolEntityMock);

        $discountQueryMock = $this->createDiscountQueryMock();
        $discountQueryMock->expects($this->once())
            ->method('findOneByIdDiscount')
            ->willReturn($discountEntityMock);

        $discountQueryContainerMock = $this->createDiscountQueryContainerMock();
        $discountQueryContainerMock->method('queryDiscount')->willReturn($discountQueryMock);

        $discountConfiguratorTransfer = $this->createDiscountConfiguratorTransfer();
        $discountConfiguratorTransfer->getDiscountGeneral()->setIdDiscount(1);

        $discountPersist = $this->createDiscountPersist($discountQueryContainerMock);
        $discountPersist->update($discountConfiguratorTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateWhenDiscountNotFoundShouldThrowException()
    {
        $this->expectException(PersistenceException::class);

        $discountQueryMock = $this->createDiscountQueryMock();
        $discountQueryMock->expects($this->once())
            ->method('findOneByIdDiscount')
            ->willReturn(null);

        $discountQueryContainerMock = $this->createDiscountQueryContainerMock();
        $discountQueryContainerMock->method('queryDiscount')->willReturn($discountQueryMock);

        $discountConfiguratorTransfer = $this->createDiscountConfiguratorTransfer();
        $discountConfiguratorTransfer->getDiscountGeneral()->setIdDiscount(1);

        $discountPersist = $this->createDiscountPersist($discountQueryContainerMock);
        $discountPersist->update($discountConfiguratorTransfer);
    }

    /**
     * @return void
     */
    public function testSaveVoucherCodesShouldCallVoucherEngineForCodeGeneration()
    {
        $discountEntityMock = $this->createDiscountEntityMock();
        $voucherPoolEntityMock = $this->createVoucherPoolEntity();

        $discountEntityMock->expects($this->exactly(1))
            ->method('getVoucherPool')
            ->willReturn($voucherPoolEntityMock);

        $discountQueryMock = $this->createDiscountQueryMock();
        $discountQueryMock->expects($this->once())
            ->method('findOneByIdDiscount')
            ->willReturn($discountEntityMock);

        $discountQueryContainerMock = $this->createDiscountQueryContainerMock();
        $discountQueryContainerMock->method('queryDiscount')->willReturn($discountQueryMock);

        $voucherEngineMock = $this->createVoucherEngineMock();
        $voucherEngineMock->expects($this->once())
            ->method('createVoucherCodes');

        $discountPersist = $this->createDiscountPersist($discountQueryContainerMock, $voucherEngineMock);

        $discountVoucherTransfer = new DiscountVoucherTransfer();
        $discountVoucherTransfer->setIdDiscount(123);
        $discountPersist->saveVoucherCodes($discountVoucherTransfer);
    }

    /**
     * @return void
     */
    public function testSaveVoucherCodesWhenDiscountNotFoundShouldThrowException()
    {
        $this->expectException(PersistenceException::class);

        $discountQueryMock = $this->createDiscountQueryMock();
        $discountQueryMock->expects($this->once())
            ->method('findOneByIdDiscount')
            ->willReturn(null);

        $discountQueryContainerMock = $this->createDiscountQueryContainerMock();
        $discountQueryContainerMock->method('queryDiscount')->willReturn($discountQueryMock);

        $discountPersist = $this->createDiscountPersist($discountQueryContainerMock);

        $discountVoucherTransfer = new DiscountVoucherTransfer();
        $discountVoucherTransfer->setIdDiscount(123);
        $discountPersist->saveVoucherCodes($discountVoucherTransfer);
    }

    /**
     * @return void
     */
    public function testToggleDiscountVisibilityShouldChangeActiveFlag()
    {
        $discountEntityMock = $this->createDiscountEntityMock();

        $discountEntityMock->expects($this->exactly(1))
            ->method('setIsActive')
            ->with(true);

        $discountQueryMock = $this->createDiscountQueryMock();
        $discountQueryMock->expects($this->once())
            ->method('findOneByIdDiscount')
            ->willReturn($discountEntityMock);

        $discountQueryContainerMock = $this->createDiscountQueryContainerMock();
        $discountQueryContainerMock->method('queryDiscount')->willReturn($discountQueryMock);

        $discountPersist = $this->createDiscountPersist($discountQueryContainerMock);

        $discountVoucherTransfer = new DiscountVoucherTransfer();
        $discountVoucherTransfer->setIdDiscount(123);
        $discountPersist->toggleDiscountVisibility(1, true);
    }

    /**
     * @return void
     */
    public function testToggleDiscountVisibilityShouldThrowExceptionWhenDiscountNotFound()
    {
        $this->expectException(PersistenceException::class);

        $discountQueryMock = $this->createDiscountQueryMock();
        $discountQueryMock->expects($this->once())
            ->method('findOneByIdDiscount')
            ->willReturn(null);

        $discountQueryContainerMock = $this->createDiscountQueryContainerMock();
        $discountQueryContainerMock->method('queryDiscount')->willReturn($discountQueryMock);

        $discountPersist = $this->createDiscountPersist($discountQueryContainerMock);

        $discountVoucherTransfer = new DiscountVoucherTransfer();
        $discountVoucherTransfer->setIdDiscount(123);
        $discountPersist->toggleDiscountVisibility(1, true);
    }

    /**
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    protected function createDiscountConfiguratorTransfer()
    {
        $discountConfiguratorTransfer = new DiscountConfiguratorTransfer();

        $discountGeneralTransfer = new DiscountGeneralTransfer();
        $discountGeneralTransfer->setDiscountType(DiscountConstants::TYPE_VOUCHER);
        $discountGeneralTransfer->setStoreRelation(new StoreRelationTransfer());
        $discountConfiguratorTransfer->setDiscountGeneral($discountGeneralTransfer);

        $discountCalculatorTransfer = new DiscountCalculatorTransfer();
        $discountConfiguratorTransfer->setDiscountCalculator($discountCalculatorTransfer);

        $discountVoucherTransfer = new DiscountVoucherTransfer();
        $discountConfiguratorTransfer->setDiscountVoucher($discountVoucherTransfer);

        $discountConditionTransfer = new DiscountConditionTransfer();
        $discountConfiguratorTransfer->setDiscountCondition($discountConditionTransfer);

        return $discountConfiguratorTransfer;
    }

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface|null $discountQueryContainerMock
     * @param \Spryker\Zed\Discount\Business\Voucher\VoucherEngineInterface|null $voucherEngineMock
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Business\Persistence\DiscountPersist
     */
    protected function createDiscountPersist(
        ?DiscountQueryContainerInterface $discountQueryContainerMock = null,
        ?VoucherEngineInterface $voucherEngineMock = null
    ) {

        if (!$discountQueryContainerMock) {
            $discountQueryContainerMock = $this->createDiscountQueryContainerMock();
        }

        if (!$voucherEngineMock) {
            $voucherEngineMock = $this->createVoucherEngineMock();
        }

        $discountStoreRelationWriterMock = $this->createDiscountStoreRelationWriterMock();
        $postCreatePlugins = [];
        $postUpdatePlugins = [];

        $discountPersistMock = $this->getMockBuilder(DiscountPersist::class)
            ->setMethods(['createDiscountEntity', 'createVoucherPoolEntity'])
            ->setConstructorArgs(
                [
                    $voucherEngineMock,
                    $discountQueryContainerMock,
                    $discountStoreRelationWriterMock,
                    $postCreatePlugins,
                    $postUpdatePlugins,
                ]
            )
            ->getMock();

        return $discountPersistMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    protected function createDiscountQueryContainerMock()
    {
        return $this->getMockBuilder(DiscountQueryContainerInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Business\Persistence\DiscountStoreRelationWriter
     */
    protected function createDiscountStoreRelationWriterMock()
    {
        return $this->getMockBuilder(DiscountStoreRelationWriter::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\Discount\Persistence\SpyDiscountQuery
     */
    protected function createDiscountQueryMock()
    {
        return $this->getMockBuilder(SpyDiscountQuery::class)->setMethods(['findOneByIdDiscount'])->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Business\Voucher\VoucherEngineInterface
     */
    protected function createVoucherEngineMock()
    {
        return $this->getMockBuilder(VoucherEngineInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\Discount\Persistence\SpyDiscount
     */
    protected function createDiscountEntityMock()
    {
        $discountEntity = $this->getMockBuilder(SpyDiscount::class)->getMock();
        $discountEntity->expects($this->once())
            ->method('save')
            ->willReturn(true);

        return $discountEntity;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool
     */
    protected function createVoucherPoolEntity()
    {
        $discountVoucherPoolEntity = $this->getMockBuilder(SpyDiscountVoucherPool::class)->getMock();
        $discountVoucherPoolEntity
            ->method('save')
            ->willReturn(true);

        return $discountVoucherPoolEntity;
    }
}
