<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Communication\Plugin\MerchantCommission;

use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\DecisionRulePluginInterface;

/**
 * @method \Spryker\Zed\MerchantCommission\MerchantCommissionConfig getConfig()
 * @method \Spryker\Zed\MerchantCommission\Business\MerchantCommissionFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantCommission\Communication\MerchantCommissionCommunicationFactory getFactory()
 */
class PriceModeMerchantCommissionOrderDecisionRulePlugin extends AbstractPlugin implements DecisionRulePluginInterface
{
    /**
     * @var string
     */
    protected const FIELD_NAME_PRICE_MODE = 'price-mode';

    /**
     * @uses \Spryker\Zed\RuleEngine\RuleEngineConfig::DATA_TYPE_STRING
     *
     * @var string
     */
    protected const DATA_TYPE_STRING = 'string';

    /**
     * {@inheritDoc}
     * - Check if the price mode in `RuleEngineClauseTransfer` equals the one provided in `MerchantCommissionCalculationRequestTransfer.priceMode`.
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $satisfyingTransfer
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     *
     * @return bool
     */
    public function isSatisfiedBy(TransferInterface $satisfyingTransfer, RuleEngineClauseTransfer $ruleEngineClauseTransfer): bool
    {
        /** @phpstan-var \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $satisfyingTransfer */
        return $this->getFacade()->isPriceModeDecisionRuleSatisfiedBy($satisfyingTransfer, $ruleEngineClauseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getFieldName(): string
    {
        return static::FIELD_NAME_PRICE_MODE;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return list<string>
     */
    public function acceptedDataTypes(): array
    {
        return [
            static::DATA_TYPE_STRING,
        ];
    }
}
