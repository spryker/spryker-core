<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelDiscountConnector\Communication\Plugin\Discount;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\DiscountExtension\Dependency\Plugin\DiscountableItemCollectorPluginInterface;
use Spryker\Zed\DiscountExtension\Dependency\Plugin\DiscountRuleWithValueOptionsPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductLabelDiscountConnector\Business\ProductLabelDiscountConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductLabelDiscountConnector\ProductLabelDiscountConnectorConfig getConfig()
 */
class ProductLabelDiscountableItemCollectorPlugin extends AbstractPlugin implements DiscountableItemCollectorPluginInterface, DiscountRuleWithValueOptionsPluginInterface
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
     * - Requires `QuoteTransfer.item.idProductAbstract` to be set.
     * - Collects discountable items from the given quote by items product labels.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return list<\Generated\Shared\Transfer\DiscountableItemTransfer>
     */
    public function collect(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer): array
    {
        return $this->getFacade()->getDiscountableItemsCollection($quoteTransfer, $clauseTransfer);
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
