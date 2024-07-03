<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantCommissionConnector\Business\Converter;

use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Spryker\Zed\PriceProductMerchantCommissionConnector\Dependency\Facade\PriceProductMerchantCommissionConnectorToMoneyFacadeInterface;

class MoneyValueConverter implements MoneyValueConverterInterface
{
    /**
     * @uses \Spryker\Zed\RuleEngine\Business\Comparator\Operator\IsInCompareOperator::EXPRESSION
     *
     * @var string
     */
    protected const EXPRESSION_IS_IN = 'is in';

    /**
     * @uses \Spryker\Zed\RuleEngine\Business\Comparator\Operator\IsNotInCompareOperator::EXPRESSION
     *
     * @var string
     */
    protected const EXPRESSION_IS_NOT_IN = 'is not in';

    /**
     * @uses \Spryker\Zed\RuleEngine\RuleEngineConfig::LIST_DELIMITER
     *
     * @phpstan-var non-empty-string
     *
     * @var string
     */
    public const LIST_DELIMITER = ';';

    /**
     * @var \Spryker\Zed\PriceProductMerchantCommissionConnector\Dependency\Facade\PriceProductMerchantCommissionConnectorToMoneyFacadeInterface
     */
    protected PriceProductMerchantCommissionConnectorToMoneyFacadeInterface $moneyFacade;

    /**
     * @param \Spryker\Zed\PriceProductMerchantCommissionConnector\Dependency\Facade\PriceProductMerchantCommissionConnectorToMoneyFacadeInterface $moneyFacade
     */
    public function __construct(PriceProductMerchantCommissionConnectorToMoneyFacadeInterface $moneyFacade)
    {
        $this->moneyFacade = $moneyFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     *
     * @return \Generated\Shared\Transfer\RuleEngineClauseTransfer
     */
    public function convertDecimalToCent(RuleEngineClauseTransfer $ruleEngineClauseTransfer): RuleEngineClauseTransfer
    {
        if (
            $ruleEngineClauseTransfer->getOperatorOrFail() === static::EXPRESSION_IS_NOT_IN ||
            $ruleEngineClauseTransfer->getOperatorOrFail() === static::EXPRESSION_IS_IN
        ) {
            return $this->convertListPrice($ruleEngineClauseTransfer);
        }

        return $this->convertSinglePrice($ruleEngineClauseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     *
     * @return \Generated\Shared\Transfer\RuleEngineClauseTransfer
     */
    protected function convertListPrice(RuleEngineClauseTransfer $ruleEngineClauseTransfer): RuleEngineClauseTransfer
    {
        $pricesExploded = explode(static::LIST_DELIMITER, $ruleEngineClauseTransfer->getValueOrFail());

        $pricesConverted = [];
        foreach ($pricesExploded as $price) {
            $pricesConverted[] = $this->moneyFacade->convertDecimalToInteger($this->formatValue($price));
        }

        return $ruleEngineClauseTransfer->setValue(implode(static::LIST_DELIMITER, $pricesConverted));
    }

    /**
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     *
     * @return \Generated\Shared\Transfer\RuleEngineClauseTransfer
     */
    protected function convertSinglePrice(RuleEngineClauseTransfer $ruleEngineClauseTransfer): RuleEngineClauseTransfer
    {
        $priceConverted = $this->moneyFacade->convertDecimalToInteger(
            $this->formatValue($ruleEngineClauseTransfer->getValueOrFail()),
        );

        return $ruleEngineClauseTransfer->setValue((string)$priceConverted);
    }

    /**
     * @param string $value
     *
     * @return float
     */
    protected function formatValue(string $value): float
    {
        return (float)str_replace(',', '.', trim($value));
    }
}
