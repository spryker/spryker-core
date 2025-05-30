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
 * @method \Spryker\Zed\CustomerDiscountConnector\Business\CustomerDiscountConnectorBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\CustomerDiscountConnector\CustomerDiscountConnectorConfig getConfig()
 * @method \Spryker\Zed\CustomerDiscountConnector\Business\CustomerDiscountConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\CustomerDiscountConnector\Communication\CustomerDiscountConnectorCommunicationFactory getFactory()
 */
class CustomerReferenceDecisionRulePlugin extends AbstractPlugin implements DecisionRulePluginInterface
{
    /**
     * @var string
     */
    protected const FIELD_NAME_CUSTOMER_REFERENCE = 'customer-reference';

    /**
     * @uses \Spryker\Zed\Discount\Business\QueryString\ComparatorOperators::TYPE_STRING
     *
     * @var string
     */
    protected const TYPE_STRING = 'string';

    /**
     * {@inheritDoc}
     * - Expects `QuoteTransfer.customer.customerReference` to be set.
     * - Checks if the customer reference matches the clause.
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
            ->createCustomerDecisionRuleChecker()
            ->isCustomerReferenceSatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer);
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
        return static::FIELD_NAME_CUSTOMER_REFERENCE;
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
            static::TYPE_STRING,
        ];
    }
}
