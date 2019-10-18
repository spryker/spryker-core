<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\Calculator;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CollectedDiscountTransfer;
use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\Calculator\Calculator;
use Spryker\Zed\Discount\Business\Distributor\DiscountableItem\DiscountableItemTransformer;
use Spryker\Zed\Discount\Business\Distributor\Distributor;
use Spryker\Zed\Discount\Business\Distributor\DistributorInterface;
use Spryker\Zed\Discount\Business\Exception\CalculatorException;
use Spryker\Zed\Discount\Business\Exception\QueryStringException;
use Spryker\Zed\Discount\Business\QueryString\ClauseValidator;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;
use Spryker\Zed\Discount\Business\QueryString\LogicalComparators;
use Spryker\Zed\Discount\Business\QueryString\OperatorProvider;
use Spryker\Zed\Discount\Business\QueryString\Specification\CollectorProvider;
use Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorSpecificationInterface;
use Spryker\Zed\Discount\Business\QueryString\Specification\MetaData\MetaDataProvider;
use Spryker\Zed\Discount\Business\QueryString\SpecificationBuilder;
use Spryker\Zed\Discount\Business\QueryString\SpecificationBuilderInterface;
use Spryker\Zed\Discount\Business\QueryString\Tokenizer;
use Spryker\Zed\Discount\Communication\Plugin\Calculator\PercentagePlugin;
use Spryker\Zed\Discount\Communication\Plugin\Collector\ItemBySkuCollectorPlugin;
use Spryker\Zed\Discount\Communication\Plugin\DecisionRule\SkuDecisionRulePlugin;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerBridge;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountAmountCalculatorPluginInterface;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface;
use Spryker\Zed\Discount\DiscountDependencyProvider;
use Spryker\Zed\Messenger\Business\MessengerFacade;
use SprykerTest\Zed\Discount\Communication\Fixtures\VoucherCollectedDiscountGroupingStrategyPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group Calculator
 * @group CalculatorTest
 * Add your own group annotations below this line
 */
class CalculatorTest extends Unit
{
    public const ITEM_GROSS_PRICE_500 = 500;

    /**
     * @return void
     */
    public function testCalculationWithoutAnyDiscountShouldReturnEmptyData()
    {
        $calculator = $this->getCalculator();

        $quoteTransfer = $this->getQuoteTransferWithTwoItems();

        $result = $calculator->calculate([], $quoteTransfer);

        $this->assertEquals(0, count($result));
    }

    /**
     * @return void
     */
    public function testCalculateShouldExecuteCollectorWhenThereIsDiscounts()
    {
        $discountTransfer = $this->createDiscountTransfer(100);
        $quoteTransfer = $this->createQuoteTransfer();

        $discountableItems = $this->createDiscountableItemsFromQuoteTransfer($quoteTransfer);

        $specificationBuilderMock = $this->createSpecificationBuilderMock();
        $collectorSpecificationMock = $this->collectorSpecificationMock();
        $collectorSpecificationMock->expects($this->once())
            ->method('collect')
            ->willReturn($discountableItems);

        $specificationBuilderMock->expects($this->once())
            ->method('buildFromQueryString')
            ->willReturn($collectorSpecificationMock);

        $calculator = $this->createCalculator($specificationBuilderMock);

        $collectedDiscounts = $calculator->calculate([$discountTransfer], $quoteTransfer);

        $this->assertNotEmpty($collectedDiscounts);
    }

    /**
     * @return void
     */
    public function testOneDiscountShouldNotBeFilteredOut()
    {
        $discountCollection = [];
        $discountCollection[] = $discount = $this->initializeDiscount(
            'name 1',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            true
        );

        $calculator = $this->getCalculator();

        $quoteTransfer = $this->getQuoteTransferWithTwoItems();

        $result = $calculator->calculate(
            $discountCollection,
            $quoteTransfer
        );

        $this->assertEquals(1, count($result));
    }

    /**
     * @param string $displayName
     * @param string $calculatorPlugin
     * @param int $amount
     * @param bool $isActive
     * @param bool $isExclusive
     *
     * @return \Generated\Shared\Transfer\DiscountTransfer
     */
    protected function initializeDiscount(
        $displayName,
        $calculatorPlugin,
        $amount,
        $isActive,
        $isExclusive = true
    ) {
        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setDisplayName($displayName);
        $discountTransfer->setAmount($amount);
        $discountTransfer->setIsActive($isActive);
        $discountTransfer->setCollectorQueryString('sku = "sku1"');
        $discountTransfer->setCalculatorPlugin($calculatorPlugin);
        $discountTransfer->setIsExclusive($isExclusive);

        return $discountTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getQuoteTransferWithTwoItems()
    {
        $quoteTransfer = new QuoteTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setUnitGrossPrice(self::ITEM_GROSS_PRICE_500);
        $itemTransfer->setSku('sku1');
        $quoteTransfer->addItem($itemTransfer);
        $quoteTransfer->addItem(clone $itemTransfer);

        return $quoteTransfer;
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Calculator\Calculator
     */
    protected function getCalculator()
    {
        $calculatorPlugins = $this->createCalculatorPlugins();

        $messengerFacade = $this->createDiscountToMessengerBridge();
        $distributor = $this->createDistributor();
        $collectorBuilder = $this->createCollectorBuilder();

        return new Calculator(
            $collectorBuilder,
            $messengerFacade,
            $distributor,
            $calculatorPlugins,
            []
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\SpecificationBuilder
     */
    protected function createCollectorBuilder()
    {
        return new SpecificationBuilder(
            $this->createTokenizer(),
            $this->createCollectorSpecificationProvider(),
            $this->createComparatorOperators(),
            $this->createClauseValidator(),
            $this->createMetaDataProvider()
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\ComparatorOperators
     */
    protected function createComparatorOperators()
    {
        $operators = (new OperatorProvider())->createComparators();

        return new ComparatorOperators($operators);
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\ClauseValidator
     */
    protected function createClauseValidator()
    {
        return new ClauseValidator(
            $this->createComparatorOperators(),
            $this->createMetaDataProvider()
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Specification\MetaData\MetaDataProvider
     */
    protected function createMetaDataProvider()
    {
        return new MetaDataProvider(
            $this->createDecisionRulePlugins(),
            $this->createComparatorOperators(),
            $this->createLogicalOperators()
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DecisionRulePluginInterface[]
     */
    protected function createDecisionRulePlugins()
    {
        return [
            new SkuDecisionRulePlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Specification\CollectorProvider
     */
    protected function createCollectorSpecificationProvider()
    {
        $collectorPlugins = $this->createCollectorPlugins();

        return new CollectorProvider($collectorPlugins);
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Plugin\CollectorPluginInterface[]
     */
    protected function createCollectorPlugins()
    {
        $collectorProviderPlugins = [];

        $collectorProviderPlugins[] = new ItemBySkuCollectorPlugin();

        return $collectorProviderPlugins;
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Distributor\Distributor
     */
    protected function createDistributor()
    {
        return new Distributor(
            $this->createDiscountableItemTransformer(),
            $this->createDiscountableItemTransformerStrategyPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Distributor\DiscountableItem\DiscountableItemTransformerInterface
     */
    protected function createDiscountableItemTransformer()
    {
        return new DiscountableItemTransformer();
    }

    /**
     * @return \Spryker\Zed\DiscountExtension\Dependency\Plugin\DiscountableItemTransformerStrategyPluginInterface[]
     */
    protected function createDiscountableItemTransformerStrategyPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\Messenger\Business\MessengerFacade
     */
    protected function createMessengerFacade()
    {
        return new MessengerFacade();
    }

    /**
     * @return \Spryker\Zed\Discount\Communication\Plugin\Calculator\PercentagePlugin
     */
    protected function createPercentageCalculator()
    {
        return new PercentagePlugin();
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerBridge
     */
    protected function createDiscountToMessengerBridge()
    {
        return new DiscountToMessengerBridge($this->createMessengerFacade());
    }

    /**
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface|null $calculatorPluginMock
     *
     * @return array
     */
    protected function createCalculatorPlugins($calculatorPluginMock = null)
    {
        $calculatorPlugins = [];

        if ($calculatorPluginMock) {
            $calculatorPlugins['test'] = $calculatorPluginMock;

            return $calculatorPlugins;
        }

        $calculatorPlugins[DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE] = $this->createPercentageCalculator();

        return $calculatorPlugins;
    }

    /**
     * @return \Spryker\Zed\DiscountExtension\Dependency\Plugin\CollectedDiscountGroupingStrategyPluginInterface[]
     */
    protected function getCollectedDiscountGroupingPlugins(): array
    {
        return [
            new VoucherCollectedDiscountGroupingStrategyPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Tokenizer
     */
    protected function createTokenizer()
    {
        return new Tokenizer();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\LogicalComparators
     */
    protected function createLogicalOperators()
    {
        return new LogicalComparators();
    }

    /**
     * @return void
     */
    public function testCalculateShouldExecuteCollectorWhenThereIsDiscountsFoo()
    {
        $discountTransfer = $this->createDiscountTransfer(100);
        $quoteTransfer = $this->createQuoteTransfer();

        $discountableItems = $this->createDiscountableItemsFromQuoteTransfer($quoteTransfer);

        $specificationBuilderMock = $this->createSpecificationBuilderMock();
        $collectorSpecificationMock = $this->collectorSpecificationMock();
        $collectorSpecificationMock->expects($this->once())
            ->method('collect')
            ->willReturn($discountableItems);

        $specificationBuilderMock->expects($this->once())
            ->method('buildFromQueryString')
            ->willReturn($collectorSpecificationMock);

        $calculator = $this->createCalculator($specificationBuilderMock);

        $collectedDiscounts = $calculator->calculate([$discountTransfer], $quoteTransfer);

        $this->assertNotEmpty($collectedDiscounts);
    }

    /**
     * @return void
     */
    public function testCalculateShouldTakeHighestAmountExclusiveDiscountWhenThereIsMoreThanOne()
    {
        $discounts[] = $this->createDiscountTransfer(70)->setIsExclusive(false);
        $discounts[] = $this->createDiscountTransfer(30)->setIsExclusive(true);
        $discounts[] = $this->createDiscountTransfer(20)->setIsExclusive(true);

        $quoteTransfer = $this->createQuoteTransfer();

        $discountableItems = $this->createDiscountableItemsFromQuoteTransfer($quoteTransfer);

        $specificationBuilderMock = $this->createSpecificationBuilderMock();
        $collectorSpecificationMock = $this->collectorSpecificationMock();
        $collectorSpecificationMock->expects($this->exactly(3))
            ->method('collect')
            ->willReturn($discountableItems);

        $specificationBuilderMock->expects($this->exactly(3))
            ->method('buildFromQueryString')
            ->willReturn($collectorSpecificationMock);

        $calculator = $this->createCalculator($specificationBuilderMock);

        $collectedDiscounts = $calculator->calculate($discounts, $quoteTransfer);

        $this->assertCount(1, $collectedDiscounts);
        $this->assertSame(30, $collectedDiscounts[0]->getDiscount()->getAmount());
    }

    /**
     * @return void
     */
    public function testCalculateShouldTakeHighestExclusiveWithinGroup()
    {
        $discounts[] = $this->createDiscountTransfer(70)->setIsExclusive(false);
        $discounts[] = $this->createDiscountTransfer(30)->setIsExclusive(true)->setVoucherCode('aktion30');
        $discounts[] = $this->createDiscountTransfer(20)->setIsExclusive(true);
        $discounts[] = $this->createDiscountTransfer(25)->setVoucherCode('aktion');
        $discounts[] = $this->createDiscountTransfer(10)->setVoucherCode('aktion10');

        $quoteTransfer = $this->createQuoteTransfer();

        $discountableItems = $this->createDiscountableItemsFromQuoteTransfer($quoteTransfer);

        $specificationBuilderMock = $this->createSpecificationBuilderMock();
        $collectorSpecificationMock = $this->collectorSpecificationMock();
        $collectorSpecificationMock->expects($this->exactly(5))
            ->method('collect')
            ->willReturn($discountableItems);

        $specificationBuilderMock->expects($this->exactly(5))
            ->method('buildFromQueryString')
            ->willReturn($collectorSpecificationMock);

        $calculator = $this->createCalculator($specificationBuilderMock);

        $collectedDiscounts = $calculator->calculate($discounts, $quoteTransfer);

        $this->assertCount(2, $collectedDiscounts);
        $discountAmounts = array_map(function (CollectedDiscountTransfer $collectedDiscountTransfer) {
            return $collectedDiscountTransfer->getDiscount()->getAmount();
        }, $collectedDiscounts);
        $this->assertEqualsCanonicalizing($discountAmounts, [20, 30]);
    }

    /**
     * @return void
     */
    public function testCalculateWhenCalculatorNotFoundShouldThrowException()
    {
        $this->expectException(CalculatorException::class);

        $discounts[] = $this->createDiscountTransfer(70)->setIsExclusive(false)->setCalculatorPlugin('non existing');

        $quoteTransfer = $this->createQuoteTransfer();
        $discountableItems = $this->createDiscountableItemsFromQuoteTransfer($quoteTransfer);

        $specificationBuilderMock = $this->createSpecificationBuilderMock();
        $collectorSpecificationMock = $this->collectorSpecificationMock();
        $collectorSpecificationMock->expects($this->exactly(1))
            ->method('collect')
            ->willReturn($discountableItems);

        $specificationBuilderMock->expects($this->exactly(1))
            ->method('buildFromQueryString')
            ->willReturn($collectorSpecificationMock);

        $calculator = $this->createCalculator($specificationBuilderMock);

        $calculator->calculate($discounts, $quoteTransfer);
    }

    /**
     * @return void
     */
    public function testCalculateWhenQueryBuilderThrowsExceptionShouldLogErrorAndReturnEmptyArray()
    {
        $discounts[] = $this->createDiscountTransfer(70)->setIsExclusive(false)->setCalculatorPlugin('non existing');

        $quoteTransfer = $this->createQuoteTransfer();
        $specificationBuilderMock = $this->createSpecificationBuilderMock();

        $specificationBuilderMock->expects($this->exactly(1))
            ->method('buildFromQueryString')
            ->willThrowException(new QueryStringException('test exception'));

        $calculator = $this->createCalculator($specificationBuilderMock);

        $collectedDiscounts = $calculator->calculate($discounts, $quoteTransfer);

        $this->assertEmpty($collectedDiscounts);
    }

    /**
     * @return void
     */
    public function testCalculateWhenDiscountableAmountPluginUsed()
    {
        $discountAmount = 100;
        $discountTransfer = $this->createDiscountTransfer($discountAmount);
        $quoteTransfer = $this->createQuoteTransfer();

        $discountableItems = $this->createDiscountableItemsFromQuoteTransfer($quoteTransfer);

        $specificationBuilderMock = $this->createSpecificationBuilderMock();
        $collectorSpecificationMock = $this->collectorSpecificationMock();
        $collectorSpecificationMock->expects($this->once())
            ->method('collect')
            ->willReturn($discountableItems);

        $specificationBuilderMock->expects($this->once())
            ->method('buildFromQueryString')
            ->willReturn($collectorSpecificationMock);

        $calculatorMock = $this->createCalculatorDiscountAmountPluginMock();

        $calculatorMock
            ->method('calculateDiscount')
            ->willReturnCallback(function ($discountableItems, DiscountTransfer $discountTransfer) {
                return $discountTransfer->getAmount();
            });

        $calculator = $this->createCalculator(
            $specificationBuilderMock,
            $this->createMessengerFacadeBridgeMock(),
            $this->createDistributorMock(),
            $calculatorMock
        );

        $collectedDiscounts = $calculator->calculate([$discountTransfer], $quoteTransfer);
        $calculatedDiscountTranser = $collectedDiscounts[0];

        $this->assertSame($calculatedDiscountTranser->getDiscount()->getAmount(), $discountTransfer->getAmount());
        $this->assertNotEmpty($collectedDiscounts);
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer()
    {
        $quoteTransfer = new QuoteTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setUnitGrossPrice(100);

        $quoteTransfer->addItem($itemTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\SpecificationBuilderInterface|null $specificationBuilderMock
     * @param \Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface|null $messengerFacadeMock
     * @param \Spryker\Zed\Discount\Business\Distributor\DistributorInterface|null $distributorMock
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface|null $calculatorPluginMock
     *
     * @return \Spryker\Zed\Discount\Business\Calculator\Calculator
     */
    protected function createCalculator(
        ?SpecificationBuilderInterface $specificationBuilderMock = null,
        ?DiscountToMessengerInterface $messengerFacadeMock = null,
        ?DistributorInterface $distributorMock = null,
        $calculatorPluginMock = null
    ) {

        if (!$specificationBuilderMock) {
            $specificationBuilderMock = $this->createSpecificationBuilderMock();
        }

        if (!$messengerFacadeMock) {
            $messengerFacadeMock = $this->createMessengerFacadeBridgeMock();
        }

        if (!$distributorMock) {
            $distributorMock = $this->createDistributorMock();
        }

        if (!$calculatorPluginMock) {
            $calculatorPluginMock = $this->createCalculatorPluginMock();
            $calculatorPluginMock
                ->method('calculateDiscount')
                ->willReturnCallback(function ($discountableItems, DiscountTransfer $discountTransfer) {
                    return $discountTransfer->getAmount();
                });
        }

        $calculatorPlugins = $this->createCalculatorPlugins($calculatorPluginMock);

        return new Calculator(
            $specificationBuilderMock,
            $messengerFacadeMock,
            $distributorMock,
            $calculatorPlugins,
            $this->getCollectedDiscountGroupingPlugins()
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Discount\Business\QueryString\SpecificationBuilderInterface
     */
    protected function createSpecificationBuilderMock()
    {
        return $this->getMockBuilder(SpecificationBuilderInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface
     */
    protected function createMessengerFacadeBridgeMock()
    {
        return $this->getMockBuilder(DiscountToMessengerInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface
     */
    protected function createCalculatorPluginMock()
    {
        return $this->getMockBuilder(DiscountCalculatorPluginInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Discount\Dependency\Plugin\DiscountAmountCalculatorPluginInterface
     */
    protected function createCalculatorDiscountAmountPluginMock()
    {
        return $this->getMockBuilder(DiscountAmountCalculatorPluginInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Discount\Business\Distributor\DistributorInterface
     */
    protected function createDistributorMock()
    {
        return $this->getMockBuilder(DistributorInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorSpecificationInterface
     */
    protected function collectorSpecificationMock()
    {
        return $this->getMockBuilder(CollectorSpecificationInterface::class)->getMock();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    protected function createDiscountableItemsFromQuoteTransfer(QuoteTransfer $quoteTransfer)
    {
        $discountableItems = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $discountableItemTransfer = new DiscountableItemTransfer();
            $discountableItemTransfer->fromArray($itemTransfer->toArray(), true);
            $discountableItemTransfer->setOriginalItemCalculatedDiscounts($itemTransfer->getCalculatedDiscounts());
            $discountableItems[] = $discountableItemTransfer;
        }

        return $discountableItems;
    }

    /**
     * @param int $amount
     *
     * @return \Generated\Shared\Transfer\DiscountTransfer
     */
    protected function createDiscountTransfer($amount)
    {
        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setCalculatorPlugin('test');
        $discountTransfer->setCollectorQueryString('sku = "*"');
        $discountTransfer->setAmount($amount);

        return $discountTransfer;
    }
}
