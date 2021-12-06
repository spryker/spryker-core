<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryDiscountConnector\Communication\Plugin\Discount;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\DiscountExtension\Dependency\Plugin\DecisionRulePluginInterface;
use Spryker\Zed\DiscountExtension\Dependency\Plugin\DiscountRuleWithValueOptionsPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CategoryDiscountConnector\Business\CategoryDiscountConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\CategoryDiscountConnector\CategoryDiscountConnectorConfig getConfig()
 */
class CategoryDecisionRulePlugin extends AbstractPlugin implements DecisionRulePluginInterface, DiscountRuleWithValueOptionsPluginInterface
{
    /**
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
     * - Requires `ItemTransfer.idProductAbstract` to be set.
     * - Requires `QuoteTransfer.item.idProductAbstract` to be set.
     * - Requires `ClauseTransfer.value` to be set.
     * - Checks if category matches clause.
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
        return $this->getFacade()->isCategorySatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer);
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
