<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\Calculator;

use ArrayObject;
use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\CollectedDiscountTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscount;
use Orm\Zed\Discount\Persistence\SpyDiscountQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Zed\Discount\Business\Calculator\CalculatorInterface;
use Spryker\Zed\Discount\Business\Calculator\Discount;
use Spryker\Zed\Discount\Business\Persistence\DiscountEntityMapperInterface;
use Spryker\Zed\Discount\Business\QueryString\SpecificationBuilderInterface;
use Spryker\Zed\Discount\Business\Voucher\VoucherValidatorInterface;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToStoreFacadeInterface;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group Calculator
 * @group DiscountTest
 * Add your own group annotations below this line
 */
class DiscountTest extends Unit
{
    /**
     * @var \Spryker\Zed\Discount\Business\Calculator\CalculatorInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $calculatorMock;

    /**
     * @var \Spryker\Zed\Discount\Dependency\Facade\DiscountToStoreFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $storeFacadeMock;

    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $discountQueryContainerMock;

    /**
     * @var \Spryker\Zed\Discount\Business\Persistence\DiscountEntityMapperInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $discountEntityMapperMock;

    /**
     * @var \Spryker\Zed\Discount\Business\Calculator\DiscountInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $discountMock;

    /**
     * @uses DiscountInterface::isDiscountApplicable()
     * @uses DiscountInterface::hydrateDiscountTransfer()
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->calculatorMock = $this->getMockBuilder(CalculatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->storeFacadeMock = $this->getMockBuilder(DiscountToStoreFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->discountQueryContainerMock = $this->getMockBuilder(DiscountQueryContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->discountEntityMapperMock = $this->getMockBuilder(DiscountEntityMapperInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $specificationBuilderMock = $this->getMockBuilder(SpecificationBuilderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $voucherValidatorMock = $this->getMockBuilder(VoucherValidatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->discountMock = $this->getMockBuilder(Discount::class)
            ->setConstructorArgs([
                $this->discountQueryContainerMock,
                $this->calculatorMock,
                $specificationBuilderMock,
                $voucherValidatorMock,
                $this->discountEntityMapperMock,
                $this->storeFacadeMock,
            ])->setMethods([
                'isDiscountApplicable',
                'hydrateDiscountTransfer',
            ])->getMock();
    }

    /**
     * @uses DiscountToStoreFacadeInterface::getStoreByName()
     * @uses CalculatorInterface::calculate()
     * @uses DiscountQueryContainerInterface::queryActiveCartRulesForStore()
     * @uses DiscountEntityMapperInterface::mapFromEntity()
     * @uses DiscountInterface::isDiscountApplicable()
     * @uses DiscountInterface::hydrateDiscountTransfer()
     *
     * @return void
     */
    public function testCalculateDiscountsRetrievesDiscount()
    {
        // Assign
        $expectedDiscount = (new DiscountTransfer())
            ->setIdDiscount(5);

        $collectedDiscount = (new CollectedDiscountTransfer())
            ->setDiscount($expectedDiscount);

        $quoteTransfer = (new QuoteTransfer())
            ->setStore($this->getCurrentStore())
            ->setUsedNotAppliedVoucherCodes([])
            ->setVoucherDiscounts(new ArrayObject([]));

        $this->calculatorMock->expects($this->any())
            ->method('calculate')
            ->willReturn([$collectedDiscount]);

        $this->storeFacadeMock->expects($this->any())
            ->method('getStoreByName')
            ->willReturn($this->getCurrentStore());

        $discountQueryMock = $this->getMockBuilder(SpyDiscountQuery::class)
            ->disableOriginalConstructor()
            ->getMock();

        $discountQueryMock->expects($this->any())
            ->method('find')
            ->willReturn(new ObjectCollection([(new SpyDiscount())]));

        $this->discountQueryContainerMock->expects($this->any())
            ->method('queryActiveCartRulesForStore')
            ->willReturn($discountQueryMock);

        $this->discountEntityMapperMock->expects($this->any())
            ->method('mapFromEntity')
            ->willReturn($expectedDiscount);

        $this->discountMock->expects($this->any())
            ->method('isDiscountApplicable')
            ->willReturn(true);

        $this->discountMock->expects($this->any())
            ->method('hydrateDiscountTransfer')
            ->willReturn($expectedDiscount);

        // Act
        $actualResult = $this->discountMock->calculate($quoteTransfer);

        // Assert
        $this->assertEquals([$expectedDiscount], $actualResult->getCartRuleDiscounts()->getArrayCopy());
    }

    /**
     * @return void
     */
    public function testCalculateDiscountsWithNonApplicableButValidVoucherShouldAddErrorMessage(): void
    {
        // Assign
        $expectedVoucherDiscount = (new DiscountTransfer())
            ->setVoucherCode('code')
            ->setDecisionRuleQueryString(
                'day-of-week = "' . (new DateTime('yesterday'))->format('l') . '"'
            )->setIdDiscount(5);

        $collectedDiscount = (new CollectedDiscountTransfer())
            ->setDiscount($expectedVoucherDiscount);

        $quoteTransfer = (new QuoteTransfer())
            ->setStore($this->getCurrentStore())
            ->setUsedNotAppliedVoucherCodes([])
            ->setVoucherDiscounts(new ArrayObject([]));

        $this->calculatorMock->expects($this->any())
            ->method('calculate')
            ->willReturn([]);

        $this->storeFacadeMock->expects($this->any())
            ->method('getStoreByName')
            ->willReturn($this->getCurrentStore());

        $discountQueryMock = $this->getMockBuilder(SpyDiscountQuery::class)
            ->disableOriginalConstructor()
            ->getMock();

        $discountQueryMock->expects($this->any())
            ->method('find')
            ->willReturn(new ObjectCollection([(new SpyDiscount())->setDiscountType(DiscountConstants::TYPE_VOUCHER)]));

        $this->discountQueryContainerMock->expects($this->any())
            ->method('queryActiveCartRulesForStore')
            ->willReturn($discountQueryMock);

        $this->discountEntityMapperMock->expects($this->any())
            ->method('mapFromEntity')
            ->willReturn($expectedVoucherDiscount);

        $this->discountMock->expects($this->any())
            ->method('isDiscountApplicable')
            ->willReturn(false);

        $this->discountMock->expects($this->any())
            ->method('hydrateDiscountTransfer')
            ->willReturn($expectedVoucherDiscount);

        // Act
        $actualResult = $this->discountMock->calculate($quoteTransfer);

        // Assert
        $this->assertCount(0, $actualResult->getVoucherDiscounts());
        $this->assertCount(1, $actualResult->getUsedNotAppliedVoucherCodes());
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function getCurrentStore()
    {
        return (new StoreTransfer())
            ->setIdStore(1)
            ->setName('DE');
    }
}
