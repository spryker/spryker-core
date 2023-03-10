<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelDiscountConnector\Communication\Plugin\Discount;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\DiscountExtension\Dependency\Plugin\DecisionRulePluginInterface;
use Spryker\Zed\DiscountExtension\Dependency\Plugin\DiscountRuleWithValueOptionsPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductLabelDiscountConnector\Business\ProductLabelDiscountConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductLabelDiscountConnector\ProductLabelDiscountConnectorConfig getConfig()
 */
class ProductLabelListDecisionRulePlugin extends AbstractPlugin implements DecisionRulePluginInterface, DiscountRuleWithValueOptionsPluginInterface
{
    /**
     * @uses \Spryker\Zed\Discount\Business\QueryString\ComparatorOperators::TYPE_LIST
     *
     * @var string
     */
    protected const TYPE_LIST = 'list';

    /**
     * @var string
     */
    protected const FIELD_NAME_PRODUCT_LABEL = 'product-label';

    /**
     * {@inheritDoc}
     * - Requires `ItemTransfer.idProductAbstract` to be set.
     * - Checks if product label matches clause.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isSatisfiedBy(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer,
        ClauseTransfer $clauseTransfer
    ): bool {
        return $this->getFacade()->isProductLabelSatisfiedByListClause($itemTransfer, $clauseTransfer);
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
        return static::FIELD_NAME_PRODUCT_LABEL;
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
            static::TYPE_LIST,
        ];
    }

    /**
     * {@inheritDoc}
     * - Retrieves product labels from Persistence.
     * - Returns an array with all label names.
     *
     * @api
     *
     * @return array<string, string>
     */
    public function getQueryStringValueOptions(): array
    {
        return $this->getFacade()->findAllLabels();
    }
}
