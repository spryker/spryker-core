<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Communication\Plugin\SalesOrderAmendment;

use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentQuoteExpanderPluginInterface;

/**
 * @method \Spryker\Zed\Shipment\Business\ShipmentFacadeInterface getFacade()
 * @method \Spryker\Zed\Shipment\Business\ShipmentBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\Shipment\ShipmentConfig getConfig()
 * @method \Spryker\Zed\Shipment\Communication\ShipmentCommunicationFactory getFactory()
 */
class ShipmentGroupsSalesOrderAmendmentQuoteExpanderPlugin extends AbstractPlugin implements SalesOrderAmendmentQuoteExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `QuoteTransfer.items` to be set for each item in `SalesOrderAmendmentQuoteCollectionTransfer.salesOrderAmendmentQuotes`.
     * - Expands each `SalesOrderAmendmentQuoteTransfer` in `SalesOrderAmendmentQuoteCollectionTransfer.salesOrderAmendmentQuotes` with shipment groups data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionTransfer $salesOrderAmendmentQuoteCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionTransfer
     */
    public function expand(
        SalesOrderAmendmentQuoteCollectionTransfer $salesOrderAmendmentQuoteCollectionTransfer
    ): SalesOrderAmendmentQuoteCollectionTransfer {
        foreach ($salesOrderAmendmentQuoteCollectionTransfer->getSalesOrderAmendmentQuotes() as $salesOrderAmendmentQuoteTransfer) {
            $shipmentGroupTransfers = $this->getBusinessFactory()->getShipmentService()->groupItemsByShipment(
                $salesOrderAmendmentQuoteTransfer->getQuoteOrFail()->getItems(),
            );
            $salesOrderAmendmentQuoteTransfer->setShipmentGroups($shipmentGroupTransfers);
        }

        return $salesOrderAmendmentQuoteCollectionTransfer;
    }
}
