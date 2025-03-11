<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesDiscountConnector\Communication\Plugin\Discount;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\DiscountExtension\Dependency\Plugin\DecisionRulePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SalesDiscountConnector\Business\SalesDiscountConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesDiscountConnector\SalesDiscountConnectorConfig getConfig()
 */
class CustomerOrderCountDecisionRulePlugin extends AbstractPlugin implements DecisionRulePluginInterface
{
    /**
     * @var string
     */
    protected const FIELD_NAME_CUSTOMER_ORDER_COUNT = 'customer-order-count';

    /**
     * @uses \Spryker\Zed\Discount\Business\QueryString\ComparatorOperators::TYPE_NUMBER
     *
     * @var string
     */
    protected const TYPE_NUMBER = 'number';

    /**
     * {@inheritDoc}
     * - Expects `QuoteTransfer.customer` to be set.
     * - Expects `QuoteTransfer.customer.idCustomer` to be set.
     * - Checks if customer's order count matches clause.
     * - Excludes current order from customer order count if `SalesDiscountConnectorConfig::isCurrentOrderExcludedFromCount()` is set to `true`.
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
        return $this->getFacade()->isCustomerOrderCountSatisfiedBy($quoteTransfer, $clauseTransfer);
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
        return static::FIELD_NAME_CUSTOMER_ORDER_COUNT;
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
            static::TYPE_NUMBER,
        ];
    }
}
