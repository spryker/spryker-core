<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductMerchantCommissionConnector\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\MerchantCommissionCalculationRequestBuilder;
use Generated\Shared\DataBuilder\RuleEngineClauseBuilder;
use Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer;
use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Spryker\Zed\MerchantCommission\Communication\Plugin\RuleEngine\MerchantCommissionItemCollectorRuleSpecificationProviderPlugin;
use Spryker\Zed\PriceProductMerchantCommissionConnector\Communication\Plugin\MerchantCommission\PriceProductMerchantCommissionItemCollectorRulePlugin;
use SprykerTest\Zed\PriceProductMerchantCommissionConnector\PriceProductMerchantCommissionConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProductMerchantCommissionConnector
 * @group Business
 * @group Facade
 * @group CollectByProductPriceTest
 * Add your own group annotations below this line
 */
class CollectByProductPriceTest extends Unit
{
    /**
     * @uses \Spryker\Zed\RuleEngine\RuleEngineDependencyProvider::PLUGINS_RULE_SPECIFICATION_PROVIDER
     *
     * @var string
     */
    protected const PLUGINS_RULE_SPECIFICATION_PROVIDER = 'PLUGINS_RULE_SPECIFICATION_PROVIDER';

    /**
     * @uses \Spryker\Zed\MerchantCommission\MerchantCommissionDependencyProvider::PLUGINS_RULE_ENGINE_COLLECTOR_RULE
     *
     * @var string
     */
    protected const PLUGINS_RULE_ENGINE_COLLECTOR_RULE = 'PLUGINS_RULE_ENGINE_COLLECTOR_RULE';

    /**
     * @var \SprykerTest\Zed\PriceProductMerchantCommissionConnector\PriceProductMerchantCommissionConnectorBusinessTester
     */
    protected PriceProductMerchantCommissionConnectorBusinessTester $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(static::PLUGINS_RULE_SPECIFICATION_PROVIDER, [
            new MerchantCommissionItemCollectorRuleSpecificationProviderPlugin(),
        ]);

        $this->tester->setDependency(static::PLUGINS_RULE_ENGINE_COLLECTOR_RULE, [
            new PriceProductMerchantCommissionItemCollectorRulePlugin(),
        ]);
    }

    /**
     * @return void
     */
    public function testShouldReturnAllItemsSatisfyingProvidedClause(): void
    {
        // Arrange
        $ruleEngineClauseTransfer = (new RuleEngineClauseBuilder([
            RuleEngineClauseTransfer::FIELD => 'item-price',
            RuleEngineClauseTransfer::VALUE => '100',
            RuleEngineClauseTransfer::OPERATOR => '>=',
            RuleEngineClauseTransfer::ACCEPTED_TYPES => ['list', 'number'],
        ]))->build();

        $merchantCommissionCalculationRequestTransfer = (new MerchantCommissionCalculationRequestBuilder())
            ->withItem([
                MerchantCommissionCalculationRequestItemTransfer::SUM_PRICE => 5000,
                MerchantCommissionCalculationRequestItemTransfer::QUANTITY => 1,
            ])
            ->withAnotherItem([
                MerchantCommissionCalculationRequestItemTransfer::SUM_PRICE => 50000,
                MerchantCommissionCalculationRequestItemTransfer::QUANTITY => 10,
            ])
            ->withAnotherItem([
                MerchantCommissionCalculationRequestItemTransfer::SUM_PRICE => 10000,
                MerchantCommissionCalculationRequestItemTransfer::QUANTITY => 1,
            ])
            ->build();

        // Act
        $collectedItems = $this->tester->getFacade()->collectByProductPrice(
            $merchantCommissionCalculationRequestTransfer,
            $ruleEngineClauseTransfer,
        );

        // Assert
        $this->assertCount(1, $collectedItems);
        $this->assertSame(10000, $collectedItems[0]->getSumPrice());
    }

    /**
     * @return void
     */
    public function testShouldReturnEmptyCollectionWhenNoItemSatisfiedProvidedClause(): void
    {
        // Arrange
        $ruleEngineClauseTransfer = (new RuleEngineClauseBuilder([
            RuleEngineClauseTransfer::FIELD => 'item-price',
            RuleEngineClauseTransfer::VALUE => '100;200;300;400',
            RuleEngineClauseTransfer::OPERATOR => 'IS IN',
            RuleEngineClauseTransfer::ACCEPTED_TYPES => ['list', 'number'],
        ]))->build();

        $merchantCommissionCalculationRequestTransfer = (new MerchantCommissionCalculationRequestBuilder())
            ->withItem([
                MerchantCommissionCalculationRequestItemTransfer::SUM_PRICE => 5000,
                MerchantCommissionCalculationRequestItemTransfer::QUANTITY => 1,
            ])
            ->withAnotherItem([
                MerchantCommissionCalculationRequestItemTransfer::SUM_PRICE => 50000,
                MerchantCommissionCalculationRequestItemTransfer::QUANTITY => 10,
            ])
            ->withAnotherItem([
                MerchantCommissionCalculationRequestItemTransfer::SUM_PRICE => 10000,
                MerchantCommissionCalculationRequestItemTransfer::QUANTITY => 5,
            ])
            ->build();

        // Act
        $collectedItems = $this->tester->getFacade()->collectByProductPrice(
            $merchantCommissionCalculationRequestTransfer,
            $ruleEngineClauseTransfer,
        );

        // Assert
        $this->assertCount(0, $collectedItems);
    }
}
