<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Discount\Business\DecisionRule;

use Orm\Zed\Discount\Persistence\Base\SpyDiscountVoucherQuery;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucher;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool;
use Spryker\Zed\Discount\Business\DecisionRule\VoucherValidator;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;

class VoucherTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testWhenZeroIsUsedForVoucherCounterShouldNotTriggerValidationError()
    {
        return;
        $discountVoucherEntity = $this->createConfiguredDiscountVoucherEntity();
        $voucher = $this->createVoucherDecisionRule($discountVoucherEntity);

        $result = $voucher->isUsable('123');

        $this->assertTrue($result->isSuccess());
    }

    /**
     * @return void
     */
    public function testWhenNullIsUsedForVoucherCounterShouldNotTriggerValidationError()
    {
        return;
        $discountVoucherEntity = $this->createConfiguredDiscountVoucherEntity();
        $discountVoucherEntity->setMaxNumberOfUses(null);
        $voucher = $this->createVoucherDecisionRule($discountVoucherEntity);

        $result = $voucher->isUsable('123');

        $this->assertTrue($result->isSuccess());
    }

    /**
     * @return void
     */
    public function testWhenNumberOfUsesLimitIsAlreadyUsedShouldTriggerError()
    {
        return;
        $discountVoucherEntity = $this->createConfiguredDiscountVoucherEntity();
        $discountVoucherEntity->setMaxNumberOfUses(1);
        $discountVoucherEntity->setNumberOfUses(1);
        $voucher = $this->createVoucherDecisionRule($discountVoucherEntity);

        $result = $voucher->isUsable('123');

        $this->assertFalse($result->isSuccess());
    }

    /**
     * @return void
     */
    public function testWhenNumberOfUsesLimitValidShouldReturnSuccess()
    {
        return;
        $discountVoucherEntity = $this->createConfiguredDiscountVoucherEntity();
        $discountVoucherEntity->setMaxNumberOfUses(2);
        $discountVoucherEntity->setNumberOfUses(1);
        $voucher = $this->createVoucherDecisionRule($discountVoucherEntity);

        $result = $voucher->isUsable('123');

        $this->assertTrue($result->isSuccess());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    protected function getQueryContainerMock(SpyDiscountVoucher $discountVoucherEntity)
    {
        return;
        $queryContainerMock = $this->getMockBuilder(DiscountQueryContainerInterface::class)->getMock();
        $voucherQueryMock = $this->getMockBuilder(SpyDiscountVoucherQuery::class)->getMock();

        $voucherQueryMock->method('findOne')->willReturn($discountVoucherEntity);

        $queryContainerMock->method('queryVoucher')->willReturn($voucherQueryMock);

        return $queryContainerMock;
    }

    /**
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucher
     */
    protected function createConfiguredDiscountVoucherEntity()
    {
        return;
        $discountVoucherEntity = $this->createDiscountVoucherEntity();
        $discountVoucherEntity->setMaxNumberOfUses(0);
        $discountVoucherEntity->setIsActive(true);

        $voucherPoolEntity = $this->createDiscountVoucherPoolEntity();
        $voucherPoolEntity->setIsActive(true);
        $discountVoucherEntity->setVoucherPool($voucherPoolEntity);

        return $discountVoucherEntity;
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscountVoucher $discountVoucherEntity
     *
     * @return \Spryker\Zed\Discount\Business\DecisionRule\VoucherValidator
     */
    protected function createVoucherDecisionRule($discountVoucherEntity)
    {
        return new VoucherValidator($this->getQueryContainerMock($discountVoucherEntity));
    }

    /**
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucher
     */
    protected function createDiscountVoucherEntity()
    {
        return new SpyDiscountVoucher();
    }

    /**
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool
     */
    protected function createDiscountVoucherPoolEntity()
    {
        return new SpyDiscountVoucherPool();
    }

}
