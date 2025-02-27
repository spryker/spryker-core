<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleNote\Communication\Plugin\SalesOrderAmendment;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentItemCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderItemCollectorPluginInterface;

/**
 * @method \Spryker\Zed\ConfigurableBundleNote\Business\ConfigurableBundleNoteFacadeInterface getFacade()
 * @method \Spryker\Zed\ConfigurableBundleNote\Business\ConfigurableBundleNoteBusinessFactory getBusinessFactory()()
 * @method \Spryker\Zed\ConfigurableBundleNote\ConfigurableBundleNoteConfig getConfig()
 */
class ConfigurableBundleNoteSalesOrderItemCollectorPlugin extends AbstractPlugin implements SalesOrderItemCollectorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `ItemTransfer.idSalesOrderItem` to be set for each item in `OrderTransfer.items`.
     * - Requires `ItemTransfer.idSalesOrderItem` to be set for each item in `SalesOrderAmendmentItemCollectionTransfer.itemsToSkip`.
     * - Iterates over `SalesOrderAmendmentItemCollectionTransfer.itemsToSkip` and compares item's configurable bundle notes with the corresponding item's configurable bundle notes from `OrderTransfer.items`.
     * - If configurable bundle notes are different, adds items to `SalesOrderAmendmentItemCollectionTransfer.itemsToUpdate` and removes from `SalesOrderAmendmentItemCollectionTransfer.itemsToSkip`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentItemCollectionTransfer $salesOrderAmendmentItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentItemCollectionTransfer
     */
    public function collect(
        OrderTransfer $orderTransfer,
        SalesOrderAmendmentItemCollectionTransfer $salesOrderAmendmentItemCollectionTransfer
    ): SalesOrderAmendmentItemCollectionTransfer {
        return $this->getBusinessFactory()
            ->createConfigurableBundleNoteSalesOrderItemCollector()
            ->collect($orderTransfer, $salesOrderAmendmentItemCollectionTransfer);
    }
}
