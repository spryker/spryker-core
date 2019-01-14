<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Shipment;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Service\Kernel\AbstractService;
use \ArrayObject;
use Spryker\Service\Shipment\Items\ItemHasOwnShipmentTransferCheckerInterface;
use Spryker\Service\Shipment\Items\ItemsGrouperInterface;

/**
 * @method \Spryker\Service\Shipment\ShipmentServiceFactory getFactory()
 */
class ShipmentService extends AbstractService implements ShipmentServiceInterface
{
    /**
     * @var \Spryker\Service\Shipment\Items\ItemsGrouperInterface
     */
    protected $itemsGrouper;

    /**
     * @var \Spryker\Service\Shipment\Items\ItemHasOwnShipmentTransferCheckerInterface
     */
    protected $itemHasOwnShipmentTransferChecker;

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    public function groupItemsByShipment(ArrayObject $itemTransfers): ArrayObject
    {
        return $this->getItemsGrouper()->groupByShipment($itemTransfers);
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated Remove strategy resolver after multiple shipment will be released.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function checkQuoteItemHasOwnShipmentTransfer(QuoteTransfer $quoteTransfer): bool
    {
        return $this->getItemHasOwnShipmentTransferChecker()->checkByQuote($quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated Remove strategy resolver after multiple shipment will be released.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function checkOrderItemHasOwnShipmentTransfer(OrderTransfer $orderTransfer): bool
    {
        return $this->getItemHasOwnShipmentTransferChecker()->checkByOrder($orderTransfer);
    }

    /**
     * @return \Spryker\Service\Shipment\Items\ItemsGrouperInterface
     */
    protected function getItemsGrouper(): ItemsGrouperInterface
    {
        if ($this->itemsGrouper === null) {
            $this->itemsGrouper = $this->getFactory()->createItemsGrouper();
        }

        return $this->itemsGrouper;
    }

    /**
     * @return \Spryker\Service\Shipment\Items\ItemHasOwnShipmentTransferCheckerInterface
     */
    protected function getItemHasOwnShipmentTransferChecker(): ItemHasOwnShipmentTransferCheckerInterface
    {
        if ($this->itemHasOwnShipmentTransferChecker === null) {
            $this->itemHasOwnShipmentTransferChecker = $this->getFactory()->createSplitDeliveryEnabledChecker();
        }

        return $this->itemHasOwnShipmentTransferChecker;
    }
}
