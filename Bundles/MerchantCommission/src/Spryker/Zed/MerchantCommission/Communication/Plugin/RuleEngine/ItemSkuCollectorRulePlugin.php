<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Communication\Plugin\RuleEngine;

use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\CollectorRulePluginInterface;

/**
 * @method \Spryker\Zed\MerchantCommission\MerchantCommissionConfig getConfig()
 * @method \Spryker\Zed\MerchantCommission\Business\MerchantCommissionFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantCommission\Communication\MerchantCommissionCommunicationFactory getFactory()
 */
class ItemSkuCollectorRulePlugin extends AbstractPlugin implements CollectorRulePluginInterface
{
    /**
     * @var string
     */
    protected const FIELD_NAME_SKU = 'sku';

    /**
     * @uses \Spryker\Zed\RuleEngine\RuleEngineConfig::DATA_TYPE_STRING
     *
     * @var string
     */
    protected const DATA_TYPE_STRING = 'string';

    /**
     * @uses \Spryker\Zed\RuleEngine\RuleEngineConfig::DATA_TYPE_LIST
     *
     * @var string
     */
    protected const DATA_TYPE_LIST = 'list';

    /**
     * {@inheritDoc}
     * - Requires `MerchantCommissionCalculationRequestTransfer.items.sku` to be set.
     * - Collects all items that match given SKU in `RuleEngineClauseTransfer`.
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $collectableTransfer
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     *
     * @return list<\Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer>
     */
    public function collect(TransferInterface $collectableTransfer, RuleEngineClauseTransfer $ruleEngineClauseTransfer): array
    {
        /** @phpstan-var \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $collectableTransfer */
        return $this->getFacade()->collectByItemSku($collectableTransfer, $ruleEngineClauseTransfer);
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
        return static::FIELD_NAME_SKU;
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
            static::DATA_TYPE_LIST,
        ];
    }
}
