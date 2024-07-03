<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantCommissionConnector\Communication\Plugin\MerchantCommission;

use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\CollectorRulePluginInterface;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RuleWithAttributesPluginInterface;

/**
 * @method \Spryker\Zed\ProductMerchantCommissionConnector\ProductMerchantCommissionConnectorConfig getConfig()
 * @method \Spryker\Zed\ProductMerchantCommissionConnector\Business\ProductMerchantCommissionConnectorFacadeInterface getFacade()
 */
class ProductAttributeMerchantCommissionItemCollectorRulePlugin extends AbstractPlugin implements CollectorRulePluginInterface, RuleWithAttributesPluginInterface
{
    /**
     * @uses \Spryker\Zed\RuleEngine\RuleEngineConfig::DATA_TYPE_NUMBER
     *
     * @var string
     */
    protected const DATA_TYPE_NUMBER = 'number';

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
    protected const FIELD_NAME_ATTRIBUTE = 'attribute';

    /**
     * {@inheritDoc}
     * - Requires `MerchantCommissionCalculationRequestTransfer.items.sku` to be set.
     * - Requires `RuleEngineClauseTransfer.attribute` to be set.
     * - Reads combined product attributes from Persistence for each product.
     * - Collects items with attributes that match the provided clause.
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
        return $this->getFacade()->collectByProductAttribute($collectableTransfer, $ruleEngineClauseTransfer);
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
        return static::FIELD_NAME_ATTRIBUTE;
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
            static::DATA_TYPE_STRING,
            static::DATA_TYPE_LIST,
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return list<string>
     */
    public function getAttributeTypes(): array
    {
        return $this->getFacade()->getProductAttributeKeys();
    }
}
