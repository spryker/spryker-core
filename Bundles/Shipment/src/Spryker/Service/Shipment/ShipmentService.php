<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Shipment;

use ArrayObject;
use Spryker\Service\Kernel\AbstractService;
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
     * @api
     *
     * @param array $shipmentFormData
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    public function createShipmentGroupTransfer(array $shipmentFormData): ShipmentGroupTransfer
    {
        $shipmentGroupTransfer = new ShipmentGroupTransfer();
        $shipmentGroupTransfer->setShipment(
            (new ShipmentTransfer)->fromArray($data, true)
        );

        foreach ($data[ShipmentForm::FIELD_ORDER_ITEMS] as $item) {
            $shipmentGroupTransfer->addItem($item);
        }

        return $shipmentGroupTransfer;
    }
}
