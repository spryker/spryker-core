<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Discount\Business\Voucher;

use Orm\Zed\Discount\Persistence\SpyDiscountVoucher;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherQuery;
use Spryker\Zed\Discount\Business\Voucher\VoucherValidator;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Discount
 * @group Business
 * @group Voucher
 * @group VoucherValidatorTest
 */
class VoucherValidatorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testValidateWhenCodeIsNotActiveShouldReturnFalse()
    {
        $discountVoucherEntityMock = $this->createDiscountVoucherEntityMock();
        $discountVoucherEntityMock->method('getIsActive')
            ->willReturn(false);

        $discountQueryContainerMock = $this->configureDiscountQueryContainerMock($discountVoucherEntityMock);

        $voucherValidator = $this->createVoucherValidator($discountQueryContainerMock);
        $isValid = $voucherValidator->isUsable('code');

        $this->assertFalse($isValid);
    }

    /**
     * @return void
     */
    public function testValidateWhenPoolIsNotSetShouldReturnFalse()
    {
        $discountVoucherEntityMock = $this->createDiscountVoucherEntityMock();

        $discountVoucherEntityMock->method('getIsActive')
            ->willReturn(true);

        $discountVoucherEntityMock->method('getVoucherPool')
            ->willReturn(null);

        $discountQueryContainerMock = $this->configureDiscountQueryContainerMock($discountVoucherEntityMock);

        $voucherValidator = $this->createVoucherValidator($discountQueryContainerMock);
        $isValid = $voucherValidator->isUsable('code');

        $this->assertFalse($isValid);
    }

    /**
     * @return void
     */
    public function testValidateWhenPoolIsNotActiveShouldReturnFalse()
    {
        $discountVoucherEntityMock = $this->createDiscountVoucherEntityMock();

        $discountVoucherEntityMock->method('getIsActive')
            ->willReturn(true);

        $voucherPoolEntity = $this->createVoucherPoolEntity();
        $voucherPoolEntity->setIsActive(false);

        $discountVoucherEntityMock->method('getVoucherPool')
            ->willReturn($voucherPoolEntity);

        $discountQueryContainerMock = $this->configureDiscountQueryContainerMock($discountVoucherEntityMock);

        $voucherValidator = $this->createVoucherValidator($discountQueryContainerMock);
        $isValid = $voucherValidator->isUsable('code');

        $this->assertFalse($isValid);
    }

    /**
     * @return void
     */
    public function testValidateWhenVoucherNumberOfUsesIsInvalidShouldReturnFalse()
    {
        $discountVoucherEntityMock = $this->createDiscountVoucherEntityMock();

        $discountVoucherEntityMock->method('getIsActive')
            ->willReturn(true);

        $discountVoucherEntityMock->method('getMaxNumberOfUses')
            ->willReturn(5);
        $discountVoucherEntityMock->method('getNumberOfUses')
            ->willReturn(5);
        $discountVoucherEntityMock->method('getMaxNumberOfUses')
            ->willReturn(5);

        $voucherPoolEntity = $this->createVoucherPoolEntity();
        $voucherPoolEntity->setIsActive(true);

        $discountVoucherEntityMock->method('getVoucherPool')
            ->willReturn($voucherPoolEntity);

        $discountQueryContainerMock = $this->configureDiscountQueryContainerMock($discountVoucherEntityMock);

        $voucherValidator = $this->createVoucherValidator($discountQueryContainerMock);
        $isValid = $voucherValidator->isUsable('code');

        $this->assertFalse($isValid);
    }

    /**
     * @return void
     */
    public function testValidateWhenVoucherIsValidShouldReturnTrue()
    {
        $discountVoucherEntityMock = $this->createDiscountVoucherEntityMock();

        $discountVoucherEntityMock->method('getIsActive')
            ->willReturn(true);

        $discountVoucherEntityMock->method('getMaxNumberOfUses')
            ->willReturn(5);
        $discountVoucherEntityMock->method('getNumberOfUses')
            ->willReturn(4);
        $discountVoucherEntityMock->method('getMaxNumberOfUses')
            ->willReturn(5);

        $voucherPoolEntity = $this->createVoucherPoolEntity();
        $voucherPoolEntity->setIsActive(true);

        $discountVoucherEntityMock->method('getVoucherPool')
            ->willReturn($voucherPoolEntity);

        $discountQueryContainerMock = $this->configureDiscountQueryContainerMock($discountVoucherEntityMock);

        $voucherValidator = $this->createVoucherValidator($discountQueryContainerMock);
        $isValid = $voucherValidator->isUsable('code');

        $this->assertTrue($isValid);
    }


    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface|null $discountQueryContainerMock
     * @param \Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface|null $messengerFacadeMock
     *
     * @return \Spryker\Zed\Discount\Business\Voucher\VoucherValidator
     */
    protected function createVoucherValidator(
        DiscountQueryContainerInterface $discountQueryContainerMock = null,
        DiscountToMessengerInterface $messengerFacadeMock = null
    ) {

        if ($discountQueryContainerMock == null) {
            $discountQueryContainerMock = $this->createDiscountQueryContainerMock();
        }

        if ($messengerFacadeMock == null) {
            $messengerFacadeMock = $this->createMessengerFacadeMock();
        }

        return new VoucherValidator($discountQueryContainerMock, $messengerFacadeMock);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    protected function createDiscountQueryContainerMock()
    {
        return $this->getMock(DiscountQueryContainerInterface::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface
     */
    protected function createMessengerFacadeMock()
    {
        return $this->getMock(DiscountToMessengerInterface::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\Discount\Persistence\SpyDiscountVoucher
     */
    protected function createDiscountVoucherEntityMock()
    {
        return $this->getMockBuilder(SpyDiscountVoucher::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\Discount\Persistence\SpyDiscountVoucherQuery
     */
    protected function createDiscountVoucherQueryMock()
    {
        return $this->getMock(SpyDiscountVoucherQuery::class);
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscountVoucher $discountVoucherEntity
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    protected function configureDiscountQueryContainerMock(SpyDiscountVoucher $discountVoucherEntity)
    {
        $discountQueryContainerMock = $this->createDiscountQueryContainerMock();

        $discountVoucherQueryMock = $this->createDiscountVoucherQueryMock();
        $discountVoucherQueryMock->method('findOne')
            ->willReturn($discountVoucherEntity);

        $discountQueryContainerMock->method('queryVoucher')
            ->willReturn($discountVoucherQueryMock);

        return $discountQueryContainerMock;
    }

    /**
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool
     */
    protected function createVoucherPoolEntity()
    {
        return new SpyDiscountVoucherPool();
    }

}
