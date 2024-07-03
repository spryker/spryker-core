<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantCommissionConnector\Business\CollectorRule;

use Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer;
use Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer;
use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Spryker\Zed\PriceProductMerchantCommissionConnector\Business\Converter\MoneyValueConverterInterface;
use Spryker\Zed\PriceProductMerchantCommissionConnector\Dependency\Facade\PriceProductMerchantCommissionConnectorToRuleEngineFacadeInterface;

class ProductPriceCollectorRule implements ProductPriceCollectorRuleInterface
{
    /**
     * @var \Spryker\Zed\PriceProductMerchantCommissionConnector\Business\Converter\MoneyValueConverterInterface
     */
    protected MoneyValueConverterInterface $moneyValueConverter;

    /**
     * @var \Spryker\Zed\PriceProductMerchantCommissionConnector\Dependency\Facade\PriceProductMerchantCommissionConnectorToRuleEngineFacadeInterface
     */
    protected PriceProductMerchantCommissionConnectorToRuleEngineFacadeInterface $ruleEngineFacade;

    /**
     * @param \Spryker\Zed\PriceProductMerchantCommissionConnector\Business\Converter\MoneyValueConverterInterface $moneyValueConverter
     * @param \Spryker\Zed\PriceProductMerchantCommissionConnector\Dependency\Facade\PriceProductMerchantCommissionConnectorToRuleEngineFacadeInterface $ruleEngineFacade
     */
    public function __construct(
        MoneyValueConverterInterface $moneyValueConverter,
        PriceProductMerchantCommissionConnectorToRuleEngineFacadeInterface $ruleEngineFacade
    ) {
        $this->moneyValueConverter = $moneyValueConverter;
        $this->ruleEngineFacade = $ruleEngineFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     *
     * @return list<\Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer>
     */
    public function collect(
        MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer,
        RuleEngineClauseTransfer $ruleEngineClauseTransfer
    ): array {
        $clonedRuleEngineClauseTransfer = (new RuleEngineClauseTransfer())->fromArray($ruleEngineClauseTransfer->toArray());
        $clonedRuleEngineClauseTransfer = $this->moneyValueConverter->convertDecimalToCent($clonedRuleEngineClauseTransfer);

        $collectedItems = [];
        foreach ($merchantCommissionCalculationRequestTransfer->getItems() as $merchantCommissionCalculationRequestItemTransfer) {
            if (
                $this->ruleEngineFacade->compare(
                    $clonedRuleEngineClauseTransfer,
                    $this->getUnitPrice($merchantCommissionCalculationRequestItemTransfer),
                )
            ) {
                $collectedItems[] = $merchantCommissionCalculationRequestItemTransfer;
            }
        }

        return $collectedItems;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer $merchantCommissionCalculationRequestItemTransfer
     *
     * @return int
     */
    protected function getUnitPrice(MerchantCommissionCalculationRequestItemTransfer $merchantCommissionCalculationRequestItemTransfer): int
    {
        return (int)($merchantCommissionCalculationRequestItemTransfer->getSumPriceOrFail() / $merchantCommissionCalculationRequestItemTransfer->getQuantityOrFail());
    }
}
