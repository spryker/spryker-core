<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDiscountConnector\Communication\Plugin\DecisionRule;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;
use Spryker\Zed\Discount\Dependency\Plugin\DecisionRulePluginInterface;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountRuleWithValueOptionsPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ShipmentDiscountConnector\Business\ShipmentDiscountConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\ShipmentDiscountConnector\ShipmentDiscountConnectorConfig getConfig()
 */
class ShipmentCarrierDecisionRulePlugin extends AbstractPlugin implements DecisionRulePluginInterface, DiscountRuleWithValueOptionsPluginInterface
{
    public const DECISION_RULE_FIELD_NAME = 'shipment-carrier';

    /**
     * {@inheritDoc}
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
    ) {
        return $this->getFacade()
            ->isCarrierSatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getFieldName()
    {
        return static::DECISION_RULE_FIELD_NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array
     */
    public function acceptedDataTypes()
    {
        return [
            ComparatorOperators::TYPE_STRING,
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array
     */
    public function getQueryStringValueOptions()
    {
        return $this->getFacade()->getCarrierList();
    }
}
