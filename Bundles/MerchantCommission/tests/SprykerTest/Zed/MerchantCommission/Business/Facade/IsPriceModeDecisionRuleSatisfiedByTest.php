<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantCommission\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\MerchantCommissionCalculationRequestBuilder;
use Generated\Shared\DataBuilder\RuleEngineClauseBuilder;
use Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer;
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
 * @group IsPriceModeDecisionRuleSatisfiedByTest
 * Add your own group annotations below this line
 */
class IsPriceModeDecisionRuleSatisfiedByTest extends Unit
{
    /**
     * @uses \Spryker\Zed\MerchantCommission\Communication\Plugin\RuleEngine\PriceModeDecisionRulePlugin::FIELD_NAME_PRICE_MODE
     *
     * @var string
     */
    protected const FIELD_NAME_PRICE_MODE = 'price-mode';

    /**
     * @uses \Spryker\Zed\MerchantCommission\Communication\Plugin\RuleEngine\PriceModeDecisionRulePlugin::DATA_TYPE_STRING
     *
     * @var string
     */
    protected const DATA_TYPE_STRING = 'string';

    /**
     * @uses \Spryker\Shared\Calculation\CalculationPriceMode::PRICE_MODE_NET
     *
     * @var string
     */
    protected const PRICE_MODE_NET = 'NET_MODE';

    /**
     * @uses \Spryker\Shared\Calculation\CalculationPriceMode::PRICE_MODE_GROSS
     *
     * @var string
     */
    protected const PRICE_MODE_GROSS = 'GROSS_MODE';

    /**
     * @var \SprykerTest\Zed\MerchantCommission\MerchantCommissionBusinessTester
     */
    protected MerchantCommissionBusinessTester $tester;

    /**
     * @dataProvider returnsCorrectResultAccordingToPriceModeInOrderAndClauseTransferDataProvider
     *
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     * @param bool $expectedResult
     *
     * @return void
     */
    public function testReturnsCorrectResultAccordingToPriceModeInOrderAndClauseTransfer(
        MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer,
        RuleEngineClauseTransfer $ruleEngineClauseTransfer,
        bool $expectedResult
    ): void {
        // Act
        $actualResult = $this->tester->getFacade()->isPriceModeDecisionRuleSatisfiedBy(
            $merchantCommissionCalculationRequestTransfer,
            $ruleEngineClauseTransfer,
        );

        // Assert
        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @return array<string, mixed>
     */
    protected function returnsCorrectResultAccordingToPriceModeInOrderAndClauseTransferDataProvider(): array
    {
        return [
            'Order price mode is gross, Clause price mode is gross' => [
                (new MerchantCommissionCalculationRequestBuilder([
                    MerchantCommissionCalculationRequestTransfer::PRICE_MODE => static::PRICE_MODE_GROSS,
                ]))->build(),
                (new RuleEngineClauseBuilder([
                    RuleEngineClauseTransfer::FIELD => static::FIELD_NAME_PRICE_MODE,
                    RuleEngineClauseTransfer::OPERATOR => '=',
                    RuleEngineClauseTransfer::VALUE => static::PRICE_MODE_GROSS,
                    RuleEngineClauseTransfer::ACCEPTED_TYPES => [static::DATA_TYPE_STRING],
                ]))->build(),
                true,
            ],
            'Order price mode is net, Clause price mode is net' => [
                (new MerchantCommissionCalculationRequestBuilder([
                    MerchantCommissionCalculationRequestTransfer::PRICE_MODE => static::PRICE_MODE_NET,
                ]))->build(),
                (new RuleEngineClauseBuilder([
                    RuleEngineClauseTransfer::FIELD => static::FIELD_NAME_PRICE_MODE,
                    RuleEngineClauseTransfer::OPERATOR => '=',
                    RuleEngineClauseTransfer::VALUE => static::PRICE_MODE_NET,
                    RuleEngineClauseTransfer::ACCEPTED_TYPES => [static::DATA_TYPE_STRING],
                ]))->build(),
                true,
            ],
            'Order price mode is gross, Clause price mode is net' => [
                (new MerchantCommissionCalculationRequestBuilder([
                    MerchantCommissionCalculationRequestTransfer::PRICE_MODE => static::PRICE_MODE_GROSS,
                ]))->build(),
                (new RuleEngineClauseBuilder([
                    RuleEngineClauseTransfer::FIELD => static::FIELD_NAME_PRICE_MODE,
                    RuleEngineClauseTransfer::OPERATOR => '=',
                    RuleEngineClauseTransfer::VALUE => static::PRICE_MODE_NET,
                    RuleEngineClauseTransfer::ACCEPTED_TYPES => [static::DATA_TYPE_STRING],
                ]))->build(),
                false,
            ],
            'Order price mode is net, Clause price mode is gross' => [
                (new MerchantCommissionCalculationRequestBuilder([
                    MerchantCommissionCalculationRequestTransfer::PRICE_MODE => static::PRICE_MODE_NET,
                ]))->build(),
                (new RuleEngineClauseBuilder([
                    RuleEngineClauseTransfer::FIELD => static::FIELD_NAME_PRICE_MODE,
                    RuleEngineClauseTransfer::OPERATOR => '=',
                    RuleEngineClauseTransfer::VALUE => static::PRICE_MODE_GROSS,
                    RuleEngineClauseTransfer::ACCEPTED_TYPES => [static::DATA_TYPE_STRING],
                ]))->build(),
                false,
            ],
        ];
    }
}
