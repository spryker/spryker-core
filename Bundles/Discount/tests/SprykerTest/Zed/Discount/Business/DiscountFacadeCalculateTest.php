<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CollectedDiscountTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscountQuery;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Zed\Discount\Business\DiscountBusinessFactory;
use Spryker\Zed\Discount\Business\DiscountFacade;
use Spryker\Zed\Discount\Business\DiscountFacadeInterface;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountableItemFilterPluginInterface;
use Spryker\Zed\Discount\DiscountDependencyProvider;
use Spryker\Zed\Kernel\Container;
use SprykerTest\Shared\Propel\Helper\InstancePoolingHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group Facade
 * @group DiscountFacadeCalculateTest
 * Add your own group annotations below this line
 */
class DiscountFacadeCalculateTest extends Unit
{
    use LocatorHelperTrait;
    use InstancePoolingHelperTrait;

    /**
     * @var string
     */
    protected const SKU_ITEM_1 = '123';

    /**
     * @var string
     */
    protected const SKU_ABSTRACT_ITEM_1 = '888';

    /**
     * @var string
     */
    protected const SKU_ITEM_2 = '431';

    /**
     * @var string
     */
    protected const SKU_ABSTRACT_ITEM_2 = '321';

    /**
     * @var \SprykerTest\Zed\Discount\DiscountBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $discounts = SpyDiscountQuery::create()->find();
        foreach ($discounts as $discountEntity) {
            $discountEntity->setIsActive(false);
            $discountEntity->save();
        }
    }

    /**
     * @return void
     */
    public function testCalculateWhenQueryStringMatchesAllItemsIncludeAllProvidedDiscounts(): void
    {
        // Arrange
        $discountTransfer = $this->tester->createDiscountTransferWithDiscountVoucherPool([
            DiscountTransfer::DECISION_RULE_QUERY_STRING => '(sku = "123" or sku = "431")',
            DiscountTransfer::COLLECTOR_QUERY_STRING => 'sku = "123" or sku is in "123' . ComparatorOperators::LIST_DELIMITER . '431"',
        ]);

        $quoteTransfer = $this->tester->createQuoteTransferWithItems([
            [
                ItemTransfer::ID => static::SKU_ITEM_1,
                ItemTransfer::ABSTRACT_SKU => static::SKU_ABSTRACT_ITEM_1,
                ItemTransfer::SKU => static::SKU_ITEM_1,
                ItemTransfer::UNIT_GROSS_PRICE => 15000,
                ItemTransfer::UNIT_PRICE => 15000,
                ItemTransfer::QUANTITY => 1,
            ],
            [
                ItemTransfer::ID => static::SKU_ITEM_2,
                ItemTransfer::ABSTRACT_SKU => static::SKU_ABSTRACT_ITEM_2,
                ItemTransfer::SKU => static::SKU_ITEM_2,
                ItemTransfer::UNIT_GROSS_PRICE => 1000,
                ItemTransfer::UNIT_PRICE => 1000,
                ItemTransfer::QUANTITY => 1,
            ],
        ]);

        // Act
        $discountFacade = $this->getFacade();
        $quoteTransfer = $discountFacade->calculateDiscounts($quoteTransfer);

        // Assert
        $cartRuleDiscounts = $quoteTransfer->getCartRuleDiscounts();

        $this->assertCount(1, $cartRuleDiscounts);
        $this->assertSame($discountTransfer->getAmount(), $cartRuleDiscounts[0]->getAmount());
    }

    /**
     * @return void
     */
    public function testCalculateWhenMinimumItemAmountNotMatchesItemsIncludeAllProvidedDiscounts(): void
    {
        // Arrange
        $this->tester->createDiscountTransferWithDiscountVoucherPool([
            DiscountTransfer::DECISION_RULE_QUERY_STRING => '(sku = "123")',
            DiscountTransfer::COLLECTOR_QUERY_STRING => 'sku = "123"',
            DiscountTransfer::DISCOUNT_TYPE => DiscountConstants::TYPE_CART_RULE,
        ], 2);

        $quoteTransfer = $this->tester->createQuoteTransferWithItems([
            [
                ItemTransfer::ID => static::SKU_ITEM_1,
                ItemTransfer::ABSTRACT_SKU => static::SKU_ABSTRACT_ITEM_1,
                ItemTransfer::SKU => static::SKU_ITEM_1,
                ItemTransfer::UNIT_GROSS_PRICE => 15000,
                ItemTransfer::UNIT_PRICE => 15000,
                ItemTransfer::QUANTITY => 1,
            ],
            [
                ItemTransfer::ID => static::SKU_ITEM_2,
                ItemTransfer::ABSTRACT_SKU => static::SKU_ABSTRACT_ITEM_2,
                ItemTransfer::SKU => static::SKU_ITEM_2,
                ItemTransfer::UNIT_GROSS_PRICE => 1000,
                ItemTransfer::UNIT_PRICE => 1000,
                ItemTransfer::QUANTITY => 1,
            ],
        ]);

        // Act
        $discountFacade = $this->getFacade();
        $quoteTransfer = $discountFacade->calculateDiscounts($quoteTransfer);

        // Assert
        $cartRuleDiscounts = $quoteTransfer->getCartRuleDiscounts();

        $this->assertCount(0, $cartRuleDiscounts);
    }

    /**
     * @return void
     */
    public function testCalculateWhenMinimumItemAmountMatchesMoreThanOneItemIncludeAllProvidedDiscounts(): void
    {
        // Arrange
        $discountTransfer = $this->tester->createDiscountTransferWithDiscountVoucherPool([
            DiscountTransfer::DECISION_RULE_QUERY_STRING => '(sku = "123" or sku = "431")',
            DiscountTransfer::COLLECTOR_QUERY_STRING => 'sku = "123" or sku is in "123' . ComparatorOperators::LIST_DELIMITER . '431"',
            DiscountTransfer::DISCOUNT_TYPE => DiscountConstants::TYPE_CART_RULE,
        ], 2);

        $quoteTransfer = $this->tester->createQuoteTransferWithItems([
            [
                ItemTransfer::ID => static::SKU_ITEM_1,
                ItemTransfer::ABSTRACT_SKU => static::SKU_ABSTRACT_ITEM_1,
                ItemTransfer::SKU => static::SKU_ITEM_1,
                ItemTransfer::UNIT_GROSS_PRICE => 15000,
                ItemTransfer::UNIT_PRICE => 15000,
                ItemTransfer::QUANTITY => 1,
            ],
            [
                ItemTransfer::ID => static::SKU_ITEM_2,
                ItemTransfer::ABSTRACT_SKU => static::SKU_ABSTRACT_ITEM_2,
                ItemTransfer::SKU => static::SKU_ITEM_2,
                ItemTransfer::UNIT_GROSS_PRICE => 1000,
                ItemTransfer::UNIT_PRICE => 1000,
                ItemTransfer::QUANTITY => 1,
            ],
        ]);

        // Act
        $discountFacade = $this->getFacade();
        $quoteTransfer = $discountFacade->calculateDiscounts($quoteTransfer);

        // Assert
        $cartRuleDiscounts = $quoteTransfer->getCartRuleDiscounts();

        $this->assertCount(1, $cartRuleDiscounts);
        $this->assertSame($discountTransfer->getAmount(), $cartRuleDiscounts[0]->getAmount());
    }

    /**
     * @return void
     */
    public function testCalculateWithEmptyDecisionRuleShouldIncludeDiscount(): void
    {
        // Arrange
        $discountTransfer = $this->tester->createDiscountTransferWithDiscountVoucherPool([
            DiscountTransfer::DECISION_RULE_QUERY_STRING => '',
            DiscountTransfer::COLLECTOR_QUERY_STRING => 'sku = "123" or sku is in "123' . ComparatorOperators::LIST_DELIMITER . '431"',
        ]);

        $quoteTransfer = $this->tester->createQuoteTransferWithItems([
            [
                ItemTransfer::ID => static::SKU_ITEM_1,
                ItemTransfer::ABSTRACT_SKU => static::SKU_ABSTRACT_ITEM_1,
                ItemTransfer::SKU => static::SKU_ITEM_1,
                ItemTransfer::UNIT_GROSS_PRICE => 15000,
                ItemTransfer::UNIT_PRICE => 15000,
                ItemTransfer::QUANTITY => 1,
            ],
            [
                ItemTransfer::ID => static::SKU_ITEM_2,
                ItemTransfer::ABSTRACT_SKU => static::SKU_ABSTRACT_ITEM_2,
                ItemTransfer::SKU => static::SKU_ITEM_2,
                ItemTransfer::UNIT_GROSS_PRICE => 1000,
                ItemTransfer::UNIT_PRICE => 1000,
                ItemTransfer::QUANTITY => 1,
            ],
        ]);

        // Act
        $discountFacade = $this->getFacade();
        $quoteTransfer = $discountFacade->calculateDiscounts($quoteTransfer);

        // Assert
        $cartRuleDiscounts = $quoteTransfer->getCartRuleDiscounts();

        $this->assertCount(1, $cartRuleDiscounts);
        $this->assertSame($discountTransfer->getAmount(), $cartRuleDiscounts[0]->getAmount());
    }

    /**
     * @return void
     */
    public function testCalculateWithIncorrectDecisionRuleShouldSkipDiscount(): void
    {
        // Arrange
        $this->tester->createDiscountTransferWithDiscountVoucherPool([
            DiscountTransfer::DECISION_RULE_QUERY_STRING => 'alskdhas jkashdj asjkdhjashdjs ahjdhas1293820',
            DiscountTransfer::COLLECTOR_QUERY_STRING => 'sku = "123" or sku is in "123' . ComparatorOperators::LIST_DELIMITER . '431"',
        ]);

        $quoteTransfer = $this->tester->createQuoteTransferWithItems([
            [
                ItemTransfer::ID => static::SKU_ITEM_1,
                ItemTransfer::ABSTRACT_SKU => static::SKU_ABSTRACT_ITEM_1,
                ItemTransfer::SKU => static::SKU_ITEM_1,
                ItemTransfer::UNIT_GROSS_PRICE => 15000,
                ItemTransfer::UNIT_PRICE => 15000,
                ItemTransfer::QUANTITY => 1,
            ],
            [
                ItemTransfer::ID => static::SKU_ITEM_2,
                ItemTransfer::ABSTRACT_SKU => static::SKU_ABSTRACT_ITEM_2,
                ItemTransfer::SKU => static::SKU_ITEM_2,
                ItemTransfer::UNIT_GROSS_PRICE => 1000,
                ItemTransfer::UNIT_PRICE => 1000,
                ItemTransfer::QUANTITY => 1,
            ],
        ]);

        // Act
        $discountFacade = $this->getFacade();
        $quoteTransfer = $discountFacade->calculateDiscounts($quoteTransfer);

        // Assert
        $cartRuleDiscounts = $quoteTransfer->getCartRuleDiscounts();

        $this->assertCount(0, $cartRuleDiscounts);
    }

    /**
     * @return void
     */
    public function testWhenMultipleVouchersFromSamePoolUsedShouldUseOnlyOnce(): void
    {
        // Arrange
        $this->disableInstancePooling();

        $discountTransfer = $this->tester->createDiscountTransferWithDiscountVoucherPool([
            DiscountTransfer::DECISION_RULE_QUERY_STRING => '',
            DiscountTransfer::COLLECTOR_QUERY_STRING => 'sku = "*"',
            DiscountTransfer::DISCOUNT_TYPE => DiscountConstants::TYPE_VOUCHER,
        ]);

        $code1 = 'code1';
        $code2 = 'code2';

        $this->tester->haveDiscountVoucher($code1, $discountTransfer);
        $this->tester->haveDiscountVoucher($code2, $discountTransfer);

        $quoteTransfer = $this->tester->createQuoteTransferWithItems([
            [
                ItemTransfer::ID => static::SKU_ITEM_1,
                ItemTransfer::ABSTRACT_SKU => static::SKU_ABSTRACT_ITEM_1,
                ItemTransfer::SKU => static::SKU_ITEM_1,
                ItemTransfer::UNIT_GROSS_PRICE => 15000,
                ItemTransfer::UNIT_PRICE => 15000,
                ItemTransfer::QUANTITY => 1,
            ],
            [
                ItemTransfer::ID => static::SKU_ITEM_2,
                ItemTransfer::ABSTRACT_SKU => static::SKU_ABSTRACT_ITEM_2,
                ItemTransfer::SKU => static::SKU_ITEM_2,
                ItemTransfer::UNIT_GROSS_PRICE => 1000,
                ItemTransfer::UNIT_PRICE => 1000,
                ItemTransfer::QUANTITY => 1,
            ],
        ]);

        $voucherDiscountTransfer = new DiscountTransfer();
        $voucherDiscountTransfer->setVoucherCode($code1);
        $quoteTransfer->addVoucherDiscount($voucherDiscountTransfer);

        $voucherDiscountTransfer = new DiscountTransfer();
        $voucherDiscountTransfer->setVoucherCode($code2);
        $quoteTransfer->addVoucherDiscount($voucherDiscountTransfer);

        // Act
        $discountFacade = $this->getFacade();
        $quoteTransfer = $discountFacade->calculateDiscounts($quoteTransfer);

        // Assert
        $this->assertCount(1, $quoteTransfer->getVoucherDiscounts());
        $this->assertSame($code1, $quoteTransfer->getVoucherDiscounts()[0]->getVoucherCode());
    }

    /**
     * @return void
     */
    public function testWhenDiscountFilterUsedShouldFilterOutItems(): void
    {
        // Arrange
        $discountTransfer = $this->tester->createDiscountTransferWithDiscountVoucherPool([
            DiscountTransfer::DECISION_RULE_QUERY_STRING => '(sku = "123" or sku = "431")',
            DiscountTransfer::COLLECTOR_QUERY_STRING => 'sku = "123" or sku is in "123' . ComparatorOperators::LIST_DELIMITER . '431"',
        ]);

        $filterPluginMock = $this->createDiscountableItemFilterPluginMock();

        $quoteTransfer = $this->tester->createQuoteTransferWithItems([
            [
                ItemTransfer::ID => static::SKU_ITEM_1,
                ItemTransfer::ABSTRACT_SKU => static::SKU_ABSTRACT_ITEM_1,
                ItemTransfer::SKU => static::SKU_ITEM_1,
                ItemTransfer::UNIT_GROSS_PRICE => 15000,
                ItemTransfer::UNIT_PRICE => 15000,
                ItemTransfer::QUANTITY => 1,
            ],
            [
                ItemTransfer::ID => static::SKU_ITEM_2,
                ItemTransfer::ABSTRACT_SKU => static::SKU_ABSTRACT_ITEM_2,
                ItemTransfer::SKU => static::SKU_ITEM_2,
                ItemTransfer::UNIT_GROSS_PRICE => 1000,
                ItemTransfer::UNIT_PRICE => 1000,
                ItemTransfer::QUANTITY => 1,
            ],
        ]);

        $filterPluginMock
            ->expects($this->once())
            ->method('filter')
            ->willReturnCallback(function (CollectedDiscountTransfer $collectedDiscountTransfer) {
                $discountableItems = new ArrayObject();
                foreach ($collectedDiscountTransfer->getDiscountableItems() as $discountableItemTransfer) {
                    if ($discountableItemTransfer->getOriginalItem()->getSku() !== '123') {
                        continue;
                    }
                    $discountableItems[] = $discountableItemTransfer;
                }
                $collectedDiscountTransfer->setDiscountableItems($discountableItems);

                return $collectedDiscountTransfer;
            });

        $discountFacade = $this->createMockedDiscountFacade($filterPluginMock);

        // Act
        $quoteTransfer = $discountFacade->calculateDiscounts($quoteTransfer);

        // Assert
        $cartRuleDiscounts = $quoteTransfer->getCartRuleDiscounts();

        $this->assertCount(1, $cartRuleDiscounts);
        $this->assertSame($discountTransfer->getAmount(), $cartRuleDiscounts[0]->getAmount());
    }

    /**
     * @return void
     */
    public function testWhenQuoteHaveUsedNotAppliedVoucherCodes(): void
    {
        // Arrange
        $discountTransfer = $this->tester->createDiscountTransferWithDiscountVoucherPool([
            DiscountTransfer::DECISION_RULE_QUERY_STRING => '',
            DiscountTransfer::COLLECTOR_QUERY_STRING => 'sku = "*"',
            DiscountTransfer::DISCOUNT_TYPE => DiscountConstants::TYPE_VOUCHER,
        ]);

        $code1 = 'code1';
        $this->tester->haveDiscountVoucher($code1, $discountTransfer);

        $quoteTransfer = $this->tester->createQuoteTransferWithItems([
            [
                ItemTransfer::ID => static::SKU_ITEM_1,
                ItemTransfer::ABSTRACT_SKU => static::SKU_ABSTRACT_ITEM_1,
                ItemTransfer::SKU => static::SKU_ITEM_1,
                ItemTransfer::UNIT_GROSS_PRICE => 15000,
                ItemTransfer::UNIT_PRICE => 15000,
                ItemTransfer::QUANTITY => 1,
            ],
            [
                ItemTransfer::ID => static::SKU_ITEM_2,
                ItemTransfer::ABSTRACT_SKU => static::SKU_ABSTRACT_ITEM_2,
                ItemTransfer::SKU => static::SKU_ITEM_2,
                ItemTransfer::UNIT_GROSS_PRICE => 1000,
                ItemTransfer::UNIT_PRICE => 1000,
                ItemTransfer::QUANTITY => 1,
            ],
        ]);
        $quoteTransfer->addUsedNotAppliedVoucherCode($code1);

        // Act
        $quoteTransfer = $this->getFacade()->calculateDiscounts($quoteTransfer);

        // Assert
        $this->assertCount(1, $quoteTransfer->getVoucherDiscounts());
        $this->assertCount(1, $quoteTransfer->getUsedNotAppliedVoucherCodes());
        $this->assertSame($code1, $quoteTransfer->getVoucherDiscounts()[0]->getVoucherCode());
    }

    /**
     * @return void
     */
    public function testCalculateDiscountsShouldNotFilterApplicableDiscounts(): void
    {
        // Arrange
        $this->tester->createDiscountTransferWithDiscountVoucherPool([
            DiscountTransfer::DECISION_RULE_QUERY_STRING => '',
            DiscountTransfer::COLLECTOR_QUERY_STRING => 'sku = "*"',
            DiscountTransfer::AMOUNT => 150,
        ]);

        $this->tester->createDiscountTransferWithDiscountVoucherPool([
            DiscountTransfer::DECISION_RULE_QUERY_STRING => sprintf('sku = "%s"', static::SKU_ITEM_1),
            DiscountTransfer::COLLECTOR_QUERY_STRING => sprintf('sku = "%s"', static::SKU_ITEM_1),
            DiscountTransfer::AMOUNT => 100,
        ]);

        $quoteTransfer = $this->tester->createQuoteTransferWithItems([
            [
                ItemTransfer::ID => static::SKU_ITEM_1,
                ItemTransfer::ABSTRACT_SKU => static::SKU_ABSTRACT_ITEM_1,
                ItemTransfer::SKU => static::SKU_ITEM_1,
                ItemTransfer::UNIT_GROSS_PRICE => 1000,
                ItemTransfer::UNIT_PRICE => 1000,
                ItemTransfer::QUANTITY => 1,
            ],
            [
                ItemTransfer::ID => static::SKU_ITEM_2,
                ItemTransfer::ABSTRACT_SKU => static::SKU_ABSTRACT_ITEM_2,
                ItemTransfer::SKU => static::SKU_ITEM_2,
                ItemTransfer::UNIT_GROSS_PRICE => 500,
                ItemTransfer::UNIT_PRICE => 500,
                ItemTransfer::QUANTITY => 1,
            ],
        ]);

        // Act
        $quoteTransfer = $this->getFacade()->calculateDiscounts($quoteTransfer);

        // Assert
        $cartRuleDiscounts = $quoteTransfer->getCartRuleDiscounts();

        $this->assertCount(2, $cartRuleDiscounts);
        $this->assertSame(150, $cartRuleDiscounts[0]->getAmount());
        $this->assertSame(100, $cartRuleDiscounts[1]->getAmount());
    }

    /**
     * @return void
     */
    public function testCalculateDiscountsShouldFilterNotApplicableDiscounts(): void
    {
        // Arrange
        $this->tester->createDiscountTransferWithDiscountVoucherPool([
            DiscountTransfer::DECISION_RULE_QUERY_STRING => '',
            DiscountTransfer::COLLECTOR_QUERY_STRING => 'sku = "*"',
            DiscountTransfer::AMOUNT => 150,
        ]);

        $this->tester->createDiscountTransferWithDiscountVoucherPool([
            DiscountTransfer::DECISION_RULE_QUERY_STRING => '',
            DiscountTransfer::COLLECTOR_QUERY_STRING => sprintf('sku = "%s"', static::SKU_ITEM_1),
            DiscountTransfer::AMOUNT => 1000,
        ]);

        $quoteTransfer = $this->tester->createQuoteTransferWithItems([
            [
                ItemTransfer::ID => static::SKU_ITEM_1,
                ItemTransfer::SKU => static::SKU_ITEM_1,
                ItemTransfer::UNIT_PRICE => 1000,
                ItemTransfer::QUANTITY => 1,
            ],
            [
                ItemTransfer::ID => static::SKU_ITEM_2,
                ItemTransfer::SKU => static::SKU_ITEM_2,
                ItemTransfer::UNIT_PRICE => 500,
                ItemTransfer::QUANTITY => 1,
            ],
        ]);

        // Act
        $quoteTransfer = $this->getFacade()->calculateDiscounts($quoteTransfer);

        // Assert
        $cartRuleDiscounts = $quoteTransfer->getCartRuleDiscounts();

        $this->assertCount(2, $cartRuleDiscounts);
        $this->assertSame(1000, $cartRuleDiscounts[0]->getAmount());
        $this->assertSame(50, $cartRuleDiscounts[1]->getAmount());
    }

    /**
     * @return void
     */
    public function testCalculateDiscountsShouldFilterRemoveDiscountsWithoutItems(): void
    {
        // Arrange
        $this->tester->createDiscountTransferWithDiscountVoucherPool([
            DiscountTransfer::DECISION_RULE_QUERY_STRING => '',
            DiscountTransfer::COLLECTOR_QUERY_STRING => sprintf('sku = "%s"', static::SKU_ITEM_1),
            DiscountTransfer::AMOUNT => 100,
        ]);

        $this->tester->createDiscountTransferWithDiscountVoucherPool([
            DiscountTransfer::DECISION_RULE_QUERY_STRING => '',
            DiscountTransfer::COLLECTOR_QUERY_STRING => sprintf('sku = "%s"', static::SKU_ITEM_1),
            DiscountTransfer::AMOUNT => 1000,
        ]);

        $quoteTransfer = $this->tester->createQuoteTransferWithItems([
            [
                ItemTransfer::ID => static::SKU_ITEM_1,
                ItemTransfer::SKU => static::SKU_ITEM_1,
                ItemTransfer::UNIT_PRICE => 1000,
                ItemTransfer::QUANTITY => 1,
            ],
        ]);

        // Act
        $quoteTransfer = $this->getFacade()->calculateDiscounts($quoteTransfer);

        // Assert
        $cartRuleDiscounts = $quoteTransfer->getCartRuleDiscounts();

        $this->assertCount(1, $cartRuleDiscounts);
        $this->assertSame(1000, $cartRuleDiscounts[0]->getAmount());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\DiscountFacadeInterface
     */
    protected function getFacade(): DiscountFacadeInterface
    {
        return $this->getLocator()->discount()->facade();
    }

    /**
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DiscountableItemFilterPluginInterface $filterPluginMock
     *
     * @return \Spryker\Zed\Discount\Business\DiscountFacadeInterface
     */
    protected function createMockedDiscountFacade(DiscountableItemFilterPluginInterface $filterPluginMock): DiscountFacadeInterface
    {
        $discountBusinessFactory = new DiscountBusinessFactory();
        $collectorPlugins = $discountBusinessFactory->getProvidedDependency(DiscountDependencyProvider::COLLECTOR_PLUGINS);
        $calculatorPlugins = $discountBusinessFactory->getProvidedDependency(DiscountDependencyProvider::CALCULATOR_PLUGINS);
        $messengerFacade = $discountBusinessFactory->getProvidedDependency(DiscountDependencyProvider::FACADE_MESSENGER);
        $decisionRulePlugins = $discountBusinessFactory->getProvidedDependency(DiscountDependencyProvider::DECISION_RULE_PLUGINS);
        $collectorStrategyPlugins = $discountBusinessFactory->getProvidedDependency(DiscountDependencyProvider::PLUGIN_COLLECTOR_STRATEGY_PLUGINS);
        $collectedDiscountGroupingPlugins = $discountBusinessFactory->getProvidedDependency(DiscountDependencyProvider::COLLECTED_DISCOUNT_GROUPING_PLUGINS);
        $applicableFilterPlugins = $discountBusinessFactory->getProvidedDependency(DiscountDependencyProvider::PLUGIN_DISCOUNT_APPLICABLE_FILTER_PLUGINS);
        $currencyFacade = $discountBusinessFactory->getProvidedDependency(DiscountDependencyProvider::FACADE_CURRENCY);
        $storeFacade = $discountBusinessFactory->getProvidedDependency(DiscountDependencyProvider::FACADE_STORE);
        $discountableItemTransformerStrategyPlugins = $discountBusinessFactory->getProvidedDependency(DiscountDependencyProvider::PLUGIN_DISCOUNTABLE_ITEM_TRANSFORMER_STRATEGY);

        $container = new Container();

        $container->set(DiscountDependencyProvider::PLUGIN_COLLECTOR_STRATEGY_PLUGINS, function () use ($collectorStrategyPlugins) {
            return $collectorStrategyPlugins;
        });

        $container->set(DiscountDependencyProvider::DECISION_RULE_PLUGINS, function () use ($decisionRulePlugins) {
            return $decisionRulePlugins;
        });

        $container->set(DiscountDependencyProvider::FACADE_MESSENGER, function () use ($messengerFacade) {
            return $messengerFacade;
        });

        $container->set(DiscountDependencyProvider::FACADE_STORE, function () use ($storeFacade) {
            return $storeFacade;
        });

        $container->set(DiscountDependencyProvider::COLLECTOR_PLUGINS, function () use ($collectorPlugins) {
            return $collectorPlugins;
        });

        $container->set(DiscountDependencyProvider::COLLECTED_DISCOUNT_GROUPING_PLUGINS, function () use ($collectedDiscountGroupingPlugins) {
            return $collectedDiscountGroupingPlugins;
        });

        $container->set(DiscountDependencyProvider::CALCULATOR_PLUGINS, function () use ($calculatorPlugins) {
            return $calculatorPlugins;
        });

        $container->set(DiscountDependencyProvider::PLUGIN_DISCOUNTABLE_ITEM_FILTER, function () use ($filterPluginMock) {
            return [
                $filterPluginMock,
            ];
        });

        $container->set(DiscountDependencyProvider::PLUGIN_DISCOUNT_APPLICABLE_FILTER_PLUGINS, function () use ($applicableFilterPlugins) {
            return $applicableFilterPlugins;
        });

        $container->set(DiscountDependencyProvider::FACADE_CURRENCY, function () use ($currencyFacade) {
            return $currencyFacade;
        });

        $container->set(DiscountDependencyProvider::PLUGIN_DISCOUNTABLE_ITEM_TRANSFORMER_STRATEGY, function () use ($discountableItemTransformerStrategyPlugins) {
            return $discountableItemTransformerStrategyPlugins;
        });

        $discountBusinessFactory->setContainer($container);

        $discountFacade = new DiscountFacade();
        $discountFacade->setFactory($discountBusinessFactory);

        return $discountFacade;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Discount\Dependency\Plugin\DiscountableItemFilterPluginInterface
     */
    protected function createDiscountableItemFilterPluginMock(): DiscountableItemFilterPluginInterface
    {
        return $this->getMockBuilder(DiscountableItemFilterPluginInterface::class)->getMock();
    }
}
