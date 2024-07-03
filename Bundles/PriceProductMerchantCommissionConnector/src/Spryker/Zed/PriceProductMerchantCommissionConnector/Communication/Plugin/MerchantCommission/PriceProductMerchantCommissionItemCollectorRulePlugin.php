<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantCommissionConnector\Communication\Plugin\MerchantCommission;

use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\CollectorRulePluginInterface;

/**
 * @method \Spryker\Zed\PriceProductMerchantCommissionConnector\PriceProductMerchantCommissionConnectorConfig getConfig()
 * @method \Spryker\Zed\PriceProductMerchantCommissionConnector\Business\PriceProductMerchantCommissionConnectorFacadeInterface getFacade()
 */
class PriceProductMerchantCommissionItemCollectorRulePlugin extends AbstractPlugin implements CollectorRulePluginInterface
{
    /**
     * @uses \Spryker\Zed\RuleEngine\RuleEngineConfig::DATA_TYPE_NUMBER
     *
     * @var string
     */
    protected const DATA_TYPE_NUMBER = 'number';

    /**
     * @uses \Spryker\Zed\RuleEngine\RuleEngineConfig::DATA_TYPE_LIST
     *
     * @var string
     */
    protected const DATA_TYPE_LIST = 'list';

    /**
     * @var string
     */
    protected const FIELD_NAME_ITEM_PRICE = 'item-price';

    /**
     * {@inheritDoc}
     * - Requires `MerchantCommissionCalculationRequestTransfer.items.sumPrice` to be set.
     * - Requires `MerchantCommissionCalculationRequestTransfer.items.quantity` to be set.
     * - Requires `RuleEngineClauseTransfer.operator` to be set.
     * - Requires `RuleEngineClauseTransfer.value` to be set.
     * - Collects items with unit price that matches the provided clause.
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $collectableTransfer
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     *
     * @return list<\Spryker\Shared\Kernel\Transfer\TransferInterface>
     */
    public function collect(TransferInterface $collectableTransfer, RuleEngineClauseTransfer $ruleEngineClauseTransfer): array
    {
        /** @phpstan-var \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $collectableTransfer */
        return $this->getFacade()->collectByProductPrice($collectableTransfer, $ruleEngineClauseTransfer);
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
        return static::FIELD_NAME_ITEM_PRICE;
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
            static::DATA_TYPE_NUMBER,
            static::DATA_TYPE_LIST,
        ];
    }
}
