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
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\DiscountVoucherTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscount;
use Orm\Zed\Discount\Persistence\SpyDiscountQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Zed\Discount\Business\Calculator\CalculatorInterface;
use Spryker\Zed\Discount\Business\Calculator\Discount;
use Spryker\Zed\Discount\Business\Calculator\DiscountInterface;
use Spryker\Zed\Discount\Business\Persistence\DiscountEntityMapperInterface;
use Spryker\Zed\Discount\Business\QueryString\SpecificationBuilderInterface;
use Spryker\Zed\Discount\Business\Voucher\VoucherValidatorInterface;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToStoreFacadeInterface;
use Spryker\Zed\Discount\DiscountDependencyProvider;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;
use SprykerTest\Zed\Discount\DiscountBusinessTester;

/**
 * Auto-generated group annotations
 *
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
     * @var string
     */
    protected const STORE_NAME = 'DE';

    /**
     * @var string
     */
    protected const CURRENCY_NAME_EUR = 'EUR';

    /**
     * @var string
     */
    protected const ITEM_SKU = 'item-sku';

    /**
     * @var \Spryker\Zed\Discount\Business\Calculator\CalculatorInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected CalculatorInterface $calculatorMock;

    /**
     * @var \Spryker\Zed\Discount\Dependency\Facade\DiscountToStoreFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected DiscountToStoreFacadeInterface $storeFacadeMock;

    /**
     * @var \Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected DiscountToMessengerInterface $messengerFacadeMock;

    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected DiscountQueryContainerInterface $discountQueryContainerMock;

    /**
     * @var \Spryker\Zed\Discount\Business\Persistence\DiscountEntityMapperInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected DiscountEntityMapperInterface $discountEntityMapperMock;

    /**
     * @var \Spryker\Zed\Discount\Business\Calculator\DiscountInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected DiscountInterface $discountMock;

    /**
     * @var \SprykerTest\Zed\Discount\DiscountBusinessTester
     */
    protected DiscountBusinessTester $tester;

    /**
     * @uses DiscountInterface::isDiscountApplicable()
     * @uses DiscountInterface::hydrateDiscountTransfer()
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->calculatorMock = $this->getMockBuilder(CalculatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->storeFacadeMock = $this->getMockBuilder(DiscountToStoreFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->messengerFacadeMock = $this->getMockBuilder(DiscountToMessengerInterface::class)
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

        $this->tester->setDependency(DiscountDependencyProvider::FACADE_MESSENGER, $this->messengerFacadeMock);

        $this->discountMock = $this->getMockBuilder(Discount::class)
            ->setConstructorArgs([
                $this->discountQueryContainerMock,
                $this->calculatorMock,
                $specificationBuilderMock,
                $voucherValidatorMock,
                $this->discountEntityMapperMock,
                $this->storeFacadeMock,
            ])->onlyMethods([
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
    public function testCalculateDiscountsRetrievesDiscount(): void
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
    public function testCalculateDiscountsWithNonApplicableButValidVoucherShouldNotApply(): void
    {
        // Assign
        $discountGeneralTransfer = $this->tester->haveDiscount([
            DiscountTransfer::DISCOUNT_TYPE => DiscountConstants::TYPE_VOUCHER,
            DiscountTransfer::DECISION_RULE_QUERY_STRING => 'day-of-week = "' . (new DateTime('yesterday'))->format('l') . '"',
        ]);
        $discountConfigurationTransfer = $this->tester->getFacade()->findHydratedDiscountConfiguratorByIdDiscount($discountGeneralTransfer->getIdDiscount());
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME]);

        $discountTransfer = (new DiscountTransfer())
            ->fromArray($discountGeneralTransfer->toArray(), true)
            ->setFkDiscountVoucherPool($discountConfigurationTransfer->getDiscountVoucher()->getFkDiscountVoucherPoolOrFail())
            ->setVoucherCode('code');
        $this->tester->haveDiscountStore($storeTransfer, $discountTransfer);

        $this->tester->haveDiscountVoucher($discountTransfer->getVoucherCode(), $discountTransfer);

        $currencyTransfer = (new CurrencyTransfer())->setCode(static::CURRENCY_NAME_EUR);

        $quoteTransfer = (new QuoteTransfer())
            ->setStore($this->getCurrentStore())
            ->setUsedNotAppliedVoucherCodes([])
            ->addItem(
                (new ItemTransfer())
                    ->setSku(static::ITEM_SKU)
                    ->setQuantity(1),
            )
            ->setCurrency($currencyTransfer)
            ->setVoucherDiscounts(new ArrayObject([$discountTransfer]));

        // Act
        $actualResult = $this->tester->getFacade()->calculateDiscounts($quoteTransfer);

        // Assert
        $this->assertCount(0, $actualResult->getVoucherDiscounts());
    }

    /**
     * @return void
     */
    public function testCalculateDiscountsWithUseNumberLimitReachedVoucherShouldAddErrorMessage(): void
    {
        // Assign
        $discountGeneralTransfer = $this->tester->haveDiscount([
            DiscountTransfer::DISCOUNT_TYPE => DiscountConstants::TYPE_VOUCHER,
        ]);
        $discountConfigurationTransfer = $this->tester->getFacade()->findHydratedDiscountConfiguratorByIdDiscount($discountGeneralTransfer->getIdDiscount());
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME]);

        $discountTransfer = (new DiscountTransfer())
            ->fromArray($discountGeneralTransfer->toArray(), true)
            ->setFkDiscountVoucherPool($discountConfigurationTransfer->getDiscountVoucher()->getFkDiscountVoucherPoolOrFail())
            ->setVoucherCode('code');
        $this->tester->haveDiscountStore($storeTransfer, $discountTransfer);

        $this->tester->haveDiscountVoucher(
            $discountTransfer->getVoucherCode(),
            $discountTransfer,
            [
                DiscountVoucherTransfer::MAX_NUMBER_OF_USES => 1,
                DiscountVoucherTransfer::NUMBER_OF_USES => 1,
            ],
        );

        $currencyTransfer = (new CurrencyTransfer())->setCode(static::CURRENCY_NAME_EUR);

        $quoteTransfer = (new QuoteTransfer())
            ->setStore($this->getCurrentStore())
            ->setUsedNotAppliedVoucherCodes([])
            ->addItem(
                (new ItemTransfer())
                    ->setSku(static::ITEM_SKU)
                    ->setQuantity(1),
            )
            ->setCurrency($currencyTransfer)
            ->setVoucherDiscounts(new ArrayObject([$discountTransfer]));

        $this->messengerFacadeMock->expects($this->once())
            ->method('addErrorMessage');

        // Act
        $actualResult = $this->tester->getFacade()->calculateDiscounts($quoteTransfer);

        // Assert
        $this->assertCount(0, $actualResult->getVoucherDiscounts());
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function getCurrentStore(): StoreTransfer
    {
        return (new StoreTransfer())
            ->setIdStore(1)
            ->setName('DE');
    }
}
