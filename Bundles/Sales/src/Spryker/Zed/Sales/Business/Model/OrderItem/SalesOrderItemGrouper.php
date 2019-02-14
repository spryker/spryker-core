<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\OrderItem;

use ArrayObject;
use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentGroupCollectionTransfer;
use Spryker\Zed\Sales\Dependency\Service\SalesToShipmentServiceInterface;

class SalesOrderItemGrouper implements SalesOrderItemGrouperInterface
{
    /**
     * @var \Spryker\Zed\Sales\Dependency\Service\SalesToShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @param \Spryker\Zed\Sales\Dependency\Service\SalesToShipmentServiceInterface $shipmentService
     */
    public function __construct(SalesToShipmentServiceInterface $shipmentService)
    {
        $this->shipmentService = $shipmentService;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupCollectionTransfer
     */
    public function getUniqueOrderItemsForShipmentGroups(OrderTransfer $orderTransfer): ShipmentGroupCollectionTransfer
    {
        $shipmentGroups = $this->shipmentService->groupItemsByShipment($orderTransfer->getItems());

        foreach ($shipmentGroups as $shipmentGroupTransfer) {
            $groupedShipmentItems = $this->getUniqueOrderItems($shipmentGroupTransfer->getItems());
            $shipmentGroupTransfer->setItems($groupedShipmentItems->getItems());
        }

        return (new ShipmentGroupCollectionTransfer())
            ->setShipmentGroups($shipmentGroups);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    public function getUniqueOrderItems(ArrayObject $itemTransfers): ItemCollectionTransfer
    {
        $existedOrderLines = new ArrayObject();
        foreach ($itemTransfers as $itemTransfer) {
            $itemTransfer->requireSku();
            if (isset($existedOrderLines[$itemTransfer->getSku()])) {
                $existedOrderLines = $this->changeQuantityOfUniqueItem($existedOrderLines, $itemTransfer);
                continue;
            }

            $existedOrderLines = $this->addItemToUniqueItemsArray($existedOrderLines, $itemTransfer);
        }

        $itemCollectionTransfer = (new ItemCollectionTransfer())
            ->setItems($existedOrderLines);

        return $itemCollectionTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $existedOrderLines
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \ArrayObject
     */
    protected function changeQuantityOfUniqueItem(ArrayObject $existedOrderLines, ItemTransfer $itemTransfer): ArrayObject
    {
        $sku = $itemTransfer->getSku();
        /**
         * @var \Generated\Shared\Transfer\ItemTransfer $existedItem
         */
        $existedItem = $existedOrderLines[$sku];
        $newQuantity = $existedItem->getQuantity() + $itemTransfer->getQuantity();
        $existedItem->setQuantity($newQuantity);
        $newPrice = $itemTransfer->getSumPrice() * $newQuantity;
        $existedItem->setSumPrice($newPrice);

        return $existedOrderLines;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $existedOrderLines
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return array
     */
    protected function addItemToUniqueItemsArray(ArrayObject $existedOrderLines, ItemTransfer $itemTransfer): ArrayObject
    {
        $existedOrderLines[$itemTransfer->getSku()] = $itemTransfer;

        return $existedOrderLines;
    }
}
