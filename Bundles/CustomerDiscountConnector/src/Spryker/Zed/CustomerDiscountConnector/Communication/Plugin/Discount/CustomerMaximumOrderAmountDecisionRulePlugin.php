<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerDiscountConnector\Communication\Plugin\Discount;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\DiscountExtension\Dependency\Plugin\DecisionRulePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CustomerDiscountConnector\Business\CustomerDiscountConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\CustomerDiscountConnector\Communication\CustomerDiscountConnectorCommunicationFactory getFactory()
 * @method \Spryker\Zed\CustomerDiscountConnector\CustomerDiscountConnectorConfig getConfig()
 * @method \Spryker\Zed\CustomerDiscountConnector\Business\CustomerDiscountConnectorBusinessFactory getBusinessFactory()
 */
class CustomerMaximumOrderAmountDecisionRulePlugin extends AbstractPlugin implements DecisionRulePluginInterface
{
    /**
     * @var string
     */
    protected const FIELD_NAME_MAX_USES_PER_CUSTOMER = 'maximum-uses-per-customer';

    /**
     * @uses \Spryker\Zed\RuleEngine\RuleEngineConfig::DATA_TYPE_NUMBER
     *
     * @var string
     */
    protected const TYPE_NUMBER = 'number';

    /**
     * {@inheritDoc}
     * - Checks if the customer order count satisfies the discount rule.
     * - Returns false for guest users.
     * - Compares the customer's order count with the specified value using the provided operator.
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
        return $this->getBusinessFactory()
            ->createCustomerOrderCountDecisionRuleChecker()
            ->isCustomerOrderCountSatisfiedBy($quoteTransfer, $clauseTransfer);
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
        return static::FIELD_NAME_MAX_USES_PER_CUSTOMER;
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
