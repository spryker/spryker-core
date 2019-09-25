<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDiscountConnector\Communication\Plugin\DiscountCollector;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;
use Spryker\Zed\Discount\Dependency\Plugin\CollectorPluginInterface;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountRuleWithValueOptionsPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ShipmentDiscountConnector\Business\ShipmentDiscountConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\ShipmentDiscountConnector\ShipmentDiscountConnectorConfig getConfig()
 */
class ItemByShipmentCarrierPlugin extends AbstractPlugin implements CollectorPluginInterface, DiscountRuleWithValueOptionsPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    public function collect(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer)
    {
        return $this->getFacade()->collectDiscountByShipmentCarrier($quoteTransfer, $clauseTransfer);
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
        return 'shipment-carrier';
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string[]
     */
    public function acceptedDataTypes()
    {
        return [
            ComparatorOperators::TYPE_NUMBER,
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
