<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryMerchantCommissionConnector\Communication\Plugin\MerchantCommission;

use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\CollectorRulePluginInterface;

/**
 * @method \Spryker\Zed\CategoryMerchantCommissionConnector\CategoryMerchantCommissionConnectorConfig getConfig()
 * @method \Spryker\Zed\CategoryMerchantCommissionConnector\Business\CategoryMerchantCommissionConnectorFacadeInterface getFacade()
 */
class CategoryMerchantCommissionItemCollectorRulePlugin extends AbstractPlugin implements CollectorRulePluginInterface
{
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
     * @var string
     */
    protected const FIELD_NAME_CATEGORY = 'category';

    /**
     * {@inheritDoc}
     * - Requires `MerchantCommissionCalculationRequestTransfer.items.sku` to be set.
     * - Gets a collection of product categories by product concrete SKUs.
     * - Gets ascended category keys for each category from the product categories collection.
     * - Collects items which categories match the provided clause.
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
        return $this->getFacade()->collectByCategory($collectableTransfer, $ruleEngineClauseTransfer);
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
        return static::FIELD_NAME_CATEGORY;
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
