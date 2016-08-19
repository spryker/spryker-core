<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Discount\Business\Voucher;

use Orm\Zed\Discount\Persistence\SpyDiscountQuery;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucher;
use Spryker\Zed\Discount\Business\Voucher\VoucherCode;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Discount
 * @group Business
 * @group Voucher
 * @group VoucherCodeTest
 */
class VoucherCodeTest extends \PHPUnit_Framework_TestCase
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

        $discountVoucherEntity->expects($this->once())
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

        $this->assertEquals(1,  $updated);
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

        $this->assertEquals(0,  $updated);
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

        $this->assertEquals(0,  $updated);
    }

    /**
     * @return void
     */
    public function testUseVoucherWhenHaveNoCounterShouldNotUpdate()
    {
        $discountVoucherEntity = $this->createDiscountVoucherMock();

        $discountVoucherEntity->expects($this->once())
            ->method('getIsActive')
            ->willReturn(true);

        $discountVoucherEntity->expects($this->once())
            ->method('getMaxNumberOfUses')
            ->willReturn(0);

        $discountQueryMock = $this->createDiscountQueryMock();
        $discountQueryMock->method('find')->willReturn([$discountVoucherEntity]);

        $discountQueryContainerMock = $this->createDiscountQueryContainerMock();
        $discountQueryContainerMock->method('queryVoucherPoolByVoucherCodes')
            ->willReturn($discountQueryMock);

        $voucherCode = $this->createVoucherCode($discountQueryContainerMock);
        $updated = $voucherCode->useCodes(['123']);

        $this->assertEquals(0,  $updated);
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

        $discountVoucherEntity->expects($this->once())
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

        $this->assertEquals(1,  $updated);
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

        $this->assertEquals(0,  $updated);
    }

    /**
     * @return void
     */
    public function testReleaseCodeWhenVoucherIsWithoutCounterShouldReturnZeroUpdatedItems()
    {
        $discountVoucherEntity = $this->createDiscountVoucherMock();

        $discountVoucherEntity->expects($this->once())
            ->method('getMaxNumberOfUses')
            ->willReturn(0);

        $discountQueryMock = $this->createDiscountQueryMock();
        $discountQueryMock->method('find')->willReturn([$discountVoucherEntity]);

        $discountQueryContainerMock = $this->createDiscountQueryContainerMock();
        $discountQueryContainerMock->method('queryVoucherPoolByVoucherCodes')
            ->willReturn($discountQueryMock);

        $voucherCode = $this->createVoucherCode($discountQueryContainerMock);
        $updated = $voucherCode->releaseUsedCodes(['123']);

        $this->assertEquals(0,  $updated);
    }

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface $discountQueryContainerMock
     *
     * @return \Spryker\Zed\Discount\Business\Voucher\VoucherCode
     */
    protected function createVoucherCode(
        DiscountQueryContainerInterface $discountQueryContainerMock = null
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
        return $this->getMock(DiscountQueryContainerInterface::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\Discount\Persistence\SpyDiscountQuery
     */
    protected function createDiscountQueryMock()
    {
        return $this->getMock(SpyDiscountQuery::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\Discount\Persistence\SpyDiscountVoucher
     */
    protected function createDiscountVoucherMock()
    {
        $discountVoucherEntity = $this->getMock(SpyDiscountVoucher::class);

        return $discountVoucherEntity;
    }

}
