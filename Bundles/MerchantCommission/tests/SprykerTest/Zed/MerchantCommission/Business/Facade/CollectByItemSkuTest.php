<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantCommission\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\MerchantCommissionCalculationRequestBuilder;
use Generated\Shared\DataBuilder\RuleEngineClauseBuilder;
use Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer;
use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use SprykerTest\Zed\MerchantCommission\MerchantCommissionBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantCommission
 * @group Business
 * @group Facade
 * @group CollectByItemSkuTest
 * Add your own group annotations below this line
 */
class CollectByItemSkuTest extends Unit
{
    /**
     * @uses \Spryker\Zed\MerchantCommission\Communication\Plugin\RuleEngine\ItemSkuCollectorRulePlugin::FIELD_NAME_SKU
     *
     * @var string
     */
    protected const FIELD_NAME_SKU = 'sku';

    /**
     * @uses \Spryker\Zed\MerchantCommission\Communication\Plugin\RuleEngine\ItemSkuCollectorRulePlugin::DATA_TYPE_STRING
     *
     * @var string
     */
    protected const DATA_TYPE_STRING = 'string';

    /**
     * @uses \Spryker\Zed\MerchantCommission\Communication\Plugin\RuleEngine\ItemSkuCollectorRulePlugin::DATA_TYPE_LIST
     *
     * @var string
     */
    protected const DATA_TYPE_LIST = 'list';

    /**
     * @var string
     */
    protected const TEST_SKU_1 = 'test-sku-1';

    /**
     * @var string
     */
    protected const TEST_SKU_2 = 'test-sku-2';

    /**
     * @var \SprykerTest\Zed\MerchantCommission\MerchantCommissionBusinessTester
     */
    protected MerchantCommissionBusinessTester $tester;

    /**
     * @return void
     */
    public function testReturnsCollectsItemsBySku(): void
    {
        // Arrange
        $merchantCommissionCalculationRequestTransfer = (new MerchantCommissionCalculationRequestBuilder())
            ->withItem([MerchantCommissionCalculationRequestItemTransfer::SKU => static::TEST_SKU_1])
            ->withAnotherItem([MerchantCommissionCalculationRequestItemTransfer::SKU => static::TEST_SKU_2])
            ->build();
        $ruleEngineClauseTransfer = (new RuleEngineClauseBuilder([
            RuleEngineClauseTransfer::FIELD => static::FIELD_NAME_SKU,
            RuleEngineClauseTransfer::OPERATOR => '=',
            RuleEngineClauseTransfer::VALUE => static::TEST_SKU_1,
            RuleEngineClauseTransfer::ACCEPTED_TYPES => [static::DATA_TYPE_STRING, static::DATA_TYPE_LIST],
        ]))->build();

        // Act
        $merchantCommissionCalculationRequestItemTransfers = $this->tester->getFacade()
            ->collectByItemSku($merchantCommissionCalculationRequestTransfer, $ruleEngineClauseTransfer);

        // Assert
        $this->assertCount(1, $merchantCommissionCalculationRequestItemTransfers);
        $this->assertSame(static::TEST_SKU_1, $merchantCommissionCalculationRequestItemTransfers[0]->getSkuOrFail());
    }

    /**
     * @return void
     */
    public function testReturnsEmptyArrayWhenNoItemSatisfiesClause(): void
    {
        // Arrange
        $merchantCommissionCalculationRequestTransfer = (new MerchantCommissionCalculationRequestBuilder())
            ->withItem([MerchantCommissionCalculationRequestItemTransfer::SKU => static::TEST_SKU_1])
            ->build();
        $ruleEngineClauseTransfer = (new RuleEngineClauseBuilder([
            RuleEngineClauseTransfer::FIELD => static::FIELD_NAME_SKU,
            RuleEngineClauseTransfer::OPERATOR => '=',
            RuleEngineClauseTransfer::VALUE => static::TEST_SKU_2,
            RuleEngineClauseTransfer::ACCEPTED_TYPES => [static::DATA_TYPE_STRING, static::DATA_TYPE_LIST],
        ]))->build();

        // Act
        $merchantCommissionCalculationRequestItemTransfers = $this->tester->getFacade()
            ->collectByItemSku($merchantCommissionCalculationRequestTransfer, $ruleEngineClauseTransfer);

        // Assert
        $this->assertCount(0, $merchantCommissionCalculationRequestItemTransfers);
    }
}
