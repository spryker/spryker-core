<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business;

use ArrayObject;
use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\CollectedDiscountTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Currency\Persistence\SpyCurrencyQuery;
use Orm\Zed\Discount\Persistence\SpyDiscount;
use Orm\Zed\Discount\Persistence\SpyDiscountAmount;
use Orm\Zed\Discount\Persistence\SpyDiscountQuery;
use Orm\Zed\Discount\Persistence\SpyDiscountStore;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucher;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool;
use Propel\Runtime\Propel;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Zed\Discount\Business\DiscountBusinessFactory;
use Spryker\Zed\Discount\Business\DiscountFacade;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountableItemFilterPluginInterface;
use Spryker\Zed\Discount\DiscountDependencyProvider;
use Spryker\Zed\Kernel\Container;
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

    /**
     * @return void
     */
    protected function setUp()
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
    public function testCalculateWhenQueryStringMatchesAllItemsIncludeAllProvidedDiscounts()
    {
        $discountEntity = $this->createDiscountEntity(
            '(sku = "123" or sku = "431")',
            'sku = "123" or sku is in "123' . ComparatorOperators::LIST_DELIMITER . '431"'
        );

        $quoteTransfer = $this->createQuoteTransfer();

        $discountFacade = $this->getFacade();
        $quoteTransfer = $discountFacade->calculateDiscounts($quoteTransfer);

        $cartRuleDiscounts = $quoteTransfer->getCartRuleDiscounts();

        $this->assertCount(1, $cartRuleDiscounts);

        $discountTransfer = $cartRuleDiscounts[0];
        $this->assertEquals($discountEntity->getAmount(), $discountTransfer->getAmount());
    }

    /**
     * @return void
     */
    public function testCalculateWhenMinimumItemAmountNotMatchesItemsIncludeAllProvidedDiscounts(): void
    {
        $this->createDiscountEntity(
            '(sku = "123")',
            'sku = "123"',
            DiscountConstants::TYPE_CART_RULE,
            2
        );

        $quoteTransfer = $this->createQuoteTransfer();

        $discountFacade = $this->getFacade();
        $quoteTransfer = $discountFacade->calculateDiscounts($quoteTransfer);

        $cartRuleDiscounts = $quoteTransfer->getCartRuleDiscounts();

        $this->assertCount(0, $cartRuleDiscounts);
    }

    /**
     * @return void
     */
    public function testCalculateWhenMinimumItemAmountMatchesMoreThanOneItemIncludeAllProvidedDiscounts(): void
    {
        $discountEntity = $this->createDiscountEntity(
            '(sku = "123" or sku = "431")',
            'sku = "123" or sku is in "123' . ComparatorOperators::LIST_DELIMITER . '431"',
            DiscountConstants::TYPE_CART_RULE,
            2
        );

        $quoteTransfer = $this->createQuoteTransfer();

        $discountFacade = $this->getFacade();
        $quoteTransfer = $discountFacade->calculateDiscounts($quoteTransfer);

        $cartRuleDiscounts = $quoteTransfer->getCartRuleDiscounts();

        $this->assertCount(1, $cartRuleDiscounts);

        $discountTransfer = current($cartRuleDiscounts);
        $this->assertEquals($discountEntity->getAmount(), $discountTransfer->getAmount());
    }

    /**
     * @return void
     */
    public function testCalculateWithEmptyDecisionRuleShouldIncludeDiscount()
    {
        $discountEntity = $this->createDiscountEntity(
            '',
            'sku = "123" or sku is in "123' . ComparatorOperators::LIST_DELIMITER . '431"'
        );

        $quoteTransfer = $this->createQuoteTransfer();

        $discountFacade = $this->getFacade();
        $quoteTransfer = $discountFacade->calculateDiscounts($quoteTransfer);

        $cartRuleDiscounts = $quoteTransfer->getCartRuleDiscounts();

        $this->assertCount(1, $cartRuleDiscounts);

        $discountTransfer = $cartRuleDiscounts[0];
        $this->assertEquals($discountEntity->getAmount(), $discountTransfer->getAmount());
    }

    /**
     * @return void
     */
    public function testCalculateWithIncorrectDecisionRuleShouldSkipDiscount()
    {
        $this->createDiscountEntity(
            'alskdhas jkashdj asjkdhjashdjs ahjdhas1293820',
            'sku = "123" or sku is in "123' . ComparatorOperators::LIST_DELIMITER . '431"'
        );

        $quoteTransfer = $this->createQuoteTransfer();

        $discountFacade = $this->getFacade();
        $quoteTransfer = $discountFacade->calculateDiscounts($quoteTransfer);

        $cartRuleDiscounts = $quoteTransfer->getCartRuleDiscounts();

        $this->assertCount(0, $cartRuleDiscounts);
    }

    /**
     * @return void
     */
    public function testWhenMultipleVouchersFromSamePoolUsedShouldUseOnlyOnce()
    {
        Propel::disableInstancePooling();

        $discountEntity = $this->createDiscountEntity(
            '',
            'sku = "*"',
            DiscountConstants::TYPE_VOUCHER
        );

        $code1 = 'code1';
        $code2 = 'code2';

        $this->createVoucherCode($code1, $discountEntity);
        $this->createVoucherCode($code2, $discountEntity);

        $quoteTransfer = $this->createQuoteTransfer();

        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setVoucherCode($code1);
        $quoteTransfer->addVoucherDiscount($discountTransfer);

        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setVoucherCode($code2);
        $quoteTransfer->addVoucherDiscount($discountTransfer);

        $discountFacade = $this->getFacade();
        $quoteTransfer = $discountFacade->calculateDiscounts($quoteTransfer);

        $discountTransfer = $quoteTransfer->getVoucherDiscounts()[0];

        $this->assertCount(1, $quoteTransfer->getVoucherDiscounts());
        $this->assertCount(1, $quoteTransfer->getUsedNotAppliedVoucherCodes());
        $this->assertEquals($code1, $discountTransfer->getVoucherCode());
    }

    /**
     * @return void
     */
    public function testWhenDiscountFilterUsedShouldFilterOutItems()
    {
        $discountEntity = $this->createDiscountEntity(
            '(sku = "123" or sku = "431")',
            'sku = "123" or sku is in "123' . ComparatorOperators::LIST_DELIMITER . '431"'
        );

        $filterPluginMock = $this->createDiscountableItemFilterPluginMock();

        $quoteTransfer = $this->createQuoteTransfer();

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

        $quoteTransfer = $discountFacade->calculateDiscounts($quoteTransfer);

        $cartRuleDiscounts = $quoteTransfer->getCartRuleDiscounts();

        $this->assertCount(1, $cartRuleDiscounts);

        $discountTransfer = $cartRuleDiscounts[0];
        $this->assertSame($discountEntity->getAmount(), $discountTransfer->getAmount());
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer()
    {
        $quoteTransfer = new QuoteTransfer();

        $quoteTransfer->setStore($this->getCurrentStore());

        $currencyTransfer = new CurrencyTransfer();
        $currencyTransfer->setCode('EUR');
        $quoteTransfer->setCurrency($currencyTransfer);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setAbstractSku('123');
        $itemTransfer->setSku('123');
        $itemTransfer->setUnitGrossPrice(15000);
        $itemTransfer->setQuantity(1);

        $quoteTransfer->addItem($itemTransfer);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setAbstractSku('321');
        $itemTransfer->setSku('431');
        $itemTransfer->setUnitGrossPrice(1000);
        $itemTransfer->setQuantity(1);

        $quoteTransfer->addItem($itemTransfer);

        return $quoteTransfer;
    }

    /**
     * @param string $decisionRuleQueryString
     * @param string $collectorQueryString
     * @param string $discountType
     * @param int $minimumItemAmount
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscount
     */
    protected function createDiscountEntity(
        $decisionRuleQueryString,
        $collectorQueryString,
        $discountType = DiscountConstants::TYPE_CART_RULE,
        $minimumItemAmount = 1
    ) {
        $discountVoucherPool = new SpyDiscountVoucherPool();
        $discountVoucherPool->setIsActive(true);
        $discountVoucherPool->setName('test');
        $discountVoucherPool->save();

        $discountEntity = new SpyDiscount();
        $discountEntity->setAmount(100);
        $discountEntity->setFkDiscountVoucherPool($discountVoucherPool->getIdDiscountVoucherPool());
        $discountEntity->setDecisionRuleQueryString($decisionRuleQueryString);
        $discountEntity->setCollectorQueryString($collectorQueryString);
        $discountEntity->setMinimumItemAmount($minimumItemAmount);

        $discountEntity->setDisplayName('display name');
        $discountEntity->setCalculatorPlugin(DiscountDependencyProvider::PLUGIN_CALCULATOR_FIXED);
        $discountEntity->setDiscountType($discountType);
        $discountEntity->setIsActive(1);
        $discountEntity->setValidFrom(new DateTime('yesterday'));
        $discountEntity->setValidTo(new DateTime('tomorrow'));
        $discountEntity->save();

        (new SpyDiscountStore())
            ->setFkStore($this->getCurrentStore()->getIdStore())
            ->setFkDiscount($discountEntity->getIdDiscount())
            ->save();

        $discountAmount = new SpyDiscountAmount();
        $currencyEntity = $this->getCurrency();
        $discountAmount->setFkCurrency($currencyEntity->getIdCurrency());
        $discountAmount->setGrossAmount(100);
        $discountAmount->setFkDiscount($discountEntity->getIdDiscount());
        $discountAmount->save();

        return $discountEntity;
    }

    /**
     * @param string $voucherCode
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucher
     */
    protected function createVoucherCode($voucherCode, SpyDiscount $discountEntity)
    {
        $voucherEntity = new SpyDiscountVoucher();
        $voucherEntity->setFkDiscountVoucherPool($discountEntity->getFkDiscountVoucherPool());
        $voucherEntity->setCode($voucherCode);
        $voucherEntity->setIsActive(true);
        $voucherEntity->save();

        return $voucherEntity;
    }

    /**
     * @return \Spryker\Zed\Discount\Business\DiscountFacadeInterface
     */
    protected function getFacade()
    {
        return $this->getLocator()->discount()->facade();
    }

    /**
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DiscountableItemFilterPluginInterface $filterPluginMock
     *
     * @return \Spryker\Zed\Discount\Business\DiscountFacadeInterface
     */
    protected function createMockedDiscountFacade(DiscountableItemFilterPluginInterface $filterPluginMock)
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
    protected function createDiscountableItemFilterPluginMock()
    {
        return $this->getMockBuilder(DiscountableItemFilterPluginInterface::class)->getMock();
    }

    /**
     * @return \Orm\Zed\Currency\Persistence\SpyCurrency
     */
    protected function getCurrency()
    {
        return SpyCurrencyQuery::create()->findOneByCode('EUR');
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
