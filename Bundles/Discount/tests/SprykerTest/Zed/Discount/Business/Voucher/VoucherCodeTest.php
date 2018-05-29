<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\Voucher;

use Codeception\Test\Unit;
use Orm\Zed\Discount\Persistence\SpyDiscountQuery;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucher;
use Spryker\Zed\Discount\Business\Voucher\VoucherCode;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group Voucher
 * @group VoucherCodeTest
 * Add your own group annotations below this line
 */
class VoucherCodeTest extends Unit
{
    /**
     * @return void
     */
    public function testUseCodesShouldPersistIncrementedNumberOfUses()
    {
        $discountVoucherEntity = $this->createDiscountVoucherMock();

        $discountVoucherEntity->expects($this->once())
            ->method('getIsActive')
            ->willReturn(true);

        $discountVoucherEntity->expects($this->once())
            ->method('save')
            ->willReturn(true);

        $discountVoucherEntity->expects($this->never())
            ->method('getMaxNumberOfUses')
            ->willReturn(10);

        $discountVoucherEntity->expects($this->once())
            ->method('getNumberOfUses')
            ->willReturn(1);

        $discountVoucherEntity->expects($this->once())
            ->method('setNumberOfUses')
            ->with(2);

        $discountQueryMock = $this->createDiscountQueryMock();
        $discountQueryMock->method('find')->willReturn([$discountVoucherEntity]);

        $discountQueryContainerMock = $this->createDiscountQueryContainerMock();
        $discountQueryContainerMock->method('queryVoucherPoolByVoucherCodes')
             ->willReturn($discountQueryMock);

        $voucherCode = $this->createVoucherCode($discountQueryContainerMock);
        $updated = $voucherCode->useCodes(['123']);

        $this->assertEquals(1, $updated);
    }

    /**
     * @return void
     */
    public function testUseVoucherCodesWhenThereIsNoVoucherShouldReturnFalse()
    {
        $discountQueryMock = $this->createDiscountQueryMock();
        $discountQueryMock->method('find')->willReturn([]);

        $discountQueryContainerMock = $this->createDiscountQueryContainerMock();
        $discountQueryContainerMock->method('queryVoucherPoolByVoucherCodes')
            ->willReturn($discountQueryMock);

        $voucherCode = $this->createVoucherCode($discountQueryContainerMock);
        $updated = $voucherCode->useCodes(['123']);

        $this->assertEquals(0, $updated);
    }

    /**
     * @return void
     */
    public function testUseVoucherWhenVoucherNotActiveShouldNotUpdate()
    {
        $discountVoucherEntity = $this->createDiscountVoucherMock();

        $discountVoucherEntity->expects($this->once())
            ->method('getIsActive')
            ->willReturn(false);

        $discountQueryMock = $this->createDiscountQueryMock();
        $discountQueryMock->method('find')->willReturn([$discountVoucherEntity]);

        $discountQueryContainerMock = $this->createDiscountQueryContainerMock();
        $discountQueryContainerMock->method('queryVoucherPoolByVoucherCodes')
            ->willReturn($discountQueryMock);

        $voucherCode = $this->createVoucherCode($discountQueryContainerMock);
        $updated = $voucherCode->useCodes(['123']);

        $this->assertEquals(0, $updated);
    }

    /**
     * @return void
     */
    public function testUseVoucherShouldUpdateCounterForUnlimited()
    {
        $discountVoucherEntity = $this->createDiscountVoucherMock();

        $discountVoucherEntity->expects($this->once())
            ->method('getIsActive')
            ->willReturn(true);

        $discountVoucherEntity->expects($this->once())
            ->method('save')
            ->willReturn(true);

        $discountVoucherEntity->expects($this->once())
            ->method('getNumberOfUses')
            ->willReturn(1);

        $discountVoucherEntity->expects($this->once())
            ->method('setNumberOfUses')
            ->with(2);

        $discountQueryMock = $this->createDiscountQueryMock();
        $discountQueryMock->method('find')->willReturn([$discountVoucherEntity]);

        $discountQueryContainerMock = $this->createDiscountQueryContainerMock();
        $discountQueryContainerMock->method('queryVoucherPoolByVoucherCodes')
            ->willReturn($discountQueryMock);

        $voucherCode = $this->createVoucherCode($discountQueryContainerMock);
        $updated = $voucherCode->useCodes(['123']);

        $this->assertEquals(1, $updated);
    }

    /**
     * @return void
     */
    public function testReleaseCodesShouldPersistDecrementedNumberOfUses()
    {
        $discountVoucherEntity = $this->createDiscountVoucherMock();

        $discountVoucherEntity->expects($this->once())
            ->method('save')
            ->willReturn(true);

        $discountVoucherEntity->expects($this->never())
            ->method('getMaxNumberOfUses')
            ->willReturn(10);

        $discountVoucherEntity->expects($this->once())
            ->method('getNumberOfUses')
            ->willReturn(2);

        $discountVoucherEntity->expects($this->once())
            ->method('setNumberOfUses')
            ->with(1);

        $discountQueryMock = $this->createDiscountQueryMock();
        $discountQueryMock->method('find')->willReturn([$discountVoucherEntity]);

        $discountQueryContainerMock = $this->createDiscountQueryContainerMock();
        $discountQueryContainerMock->method('queryVoucherPoolByVoucherCodes')
            ->willReturn($discountQueryMock);

        $voucherCode = $this->createVoucherCode($discountQueryContainerMock);
        $updated = $voucherCode->releaseUsedCodes(['123']);

        $this->assertEquals(1, $updated);
    }

    /**
     * @return void
     */
    public function testReleaseCodesNotVouchersFoundShouldReturnZeroUpdatedItems()
    {
        $discountQueryMock = $this->createDiscountQueryMock();
        $discountQueryMock->method('find')->willReturn([]);

        $discountQueryContainerMock = $this->createDiscountQueryContainerMock();
        $discountQueryContainerMock->method('queryVoucherPoolByVoucherCodes')
            ->willReturn($discountQueryMock);

        $voucherCode = $this->createVoucherCode($discountQueryContainerMock);
        $updated = $voucherCode->releaseUsedCodes(['123']);

        $this->assertEquals(0, $updated);
    }

    /**
     * @return void
     */
    public function testReleaseCodeWhenVoucherIsWithoutCounterShouldReturnZeroUpdatedItems()
    {
        $discountVoucherEntity = $this->createDiscountVoucherMock();

        $discountVoucherEntity->expects($this->never())
            ->method('getMaxNumberOfUses')
            ->willReturn(0);

        $discountQueryMock = $this->createDiscountQueryMock();
        $discountQueryMock->method('find')->willReturn([$discountVoucherEntity]);

        $discountQueryContainerMock = $this->createDiscountQueryContainerMock();
        $discountQueryContainerMock->method('queryVoucherPoolByVoucherCodes')
            ->willReturn($discountQueryMock);

        $voucherCode = $this->createVoucherCode($discountQueryContainerMock);
        $updated = $voucherCode->releaseUsedCodes(['123']);

        $this->assertEquals(0, $updated);
    }

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface|null $discountQueryContainerMock
     *
     * @return \Spryker\Zed\Discount\Business\Voucher\VoucherCode
     */
    protected function createVoucherCode(
        ?DiscountQueryContainerInterface $discountQueryContainerMock = null
    ) {

        if (!$discountQueryContainerMock) {
            $discountQueryContainerMock = $this->createDiscountQueryContainerMock();
        }

        return new VoucherCode($discountQueryContainerMock);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    protected function createDiscountQueryContainerMock()
    {
        return $this->getMockBuilder(DiscountQueryContainerInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\Discount\Persistence\SpyDiscountQuery
     */
    protected function createDiscountQueryMock()
    {
        return $this->getMockBuilder(SpyDiscountQuery::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\Discount\Persistence\SpyDiscountVoucher
     */
    protected function createDiscountVoucherMock()
    {
        $discountVoucherEntity = $this->getMockBuilder(SpyDiscountVoucher::class)->getMock();

        return $discountVoucherEntity;
    }
}
