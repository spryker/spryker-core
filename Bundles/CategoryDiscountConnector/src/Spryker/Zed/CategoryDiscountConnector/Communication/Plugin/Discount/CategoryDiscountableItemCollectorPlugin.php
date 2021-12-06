<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryDiscountConnector\Communication\Plugin\Discount;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\DiscountExtension\Dependency\Plugin\DiscountableItemCollectorPluginInterface;
use Spryker\Zed\DiscountExtension\Dependency\Plugin\DiscountRuleWithValueOptionsPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CategoryDiscountConnector\Business\CategoryDiscountConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\CategoryDiscountConnector\CategoryDiscountConnectorConfig getConfig()
 */
class CategoryDiscountableItemCollectorPlugin extends AbstractPlugin implements DiscountableItemCollectorPluginInterface, DiscountRuleWithValueOptionsPluginInterface
{
    /**
     * @uses \Spryker\Zed\CategoryDiscountConnector\Communication\Plugin\Discount\CategoryDecisionRulePlugin::FIELD_NAME_CATEGORY
     *
     * @var string
     */
    protected const FIELD_NAME_CATEGORY = 'category';

    /**
     * @uses \Spryker\Zed\Discount\Business\QueryString\ComparatorOperators::TYPE_LIST
     *
     * @var string
     */
    protected const TYPE_LIST = 'list';

    /**
     * {@inheritDoc}
     * - Requires `QuoteTransfer.item.idProductAbstract` to be set.
     * - Requires `QuoteTransfer.priceMode` to be set.
     * - Requires `ClauseTransfer.value` to be set.
     * - Requires one of either `QuoteTransfer.item.unitNetPrice` or `QuoteTransfer.item.unitGrossPrice` to be set depending on price mode.
     * - Collects discountable items from the given quote by item categories.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return array<\Generated\Shared\Transfer\DiscountableItemTransfer>
     */
    public function collect(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer): array
    {
        return $this->getFacade()->getDiscountableItemsByCategory($quoteTransfer, $clauseTransfer);
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
     * @return array<string>
     */
    public function acceptedDataTypes(): array
    {
        return [
            static::TYPE_LIST,
        ];
    }

    /**
     * {@inheritDoc}
     * - Retrieves categories by the current locale from Persistence.
     * - Returns assoc array [category key => category name].
     *
     * @api
     *
     * @return array<int|string, string>
     */
    public function getQueryStringValueOptions(): array
    {
        return $this->getFacade()->getCategoryNamesIndexedByCategoryKey();
    }
}
