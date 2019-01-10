<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Shipment;

use Spryker\Service\Kernel\AbstractService;
use \ArrayObject;
use Spryker\Service\Shipment\Model\ItemsGrouperInterface;

/**
 * @method \Spryker\Service\Shipment\ShipmentServiceFactory getFactory()
 */
class ShipmentService extends AbstractService implements ShipmentServiceInterface
{
    /**
     * @var \Spryker\Service\Shipment\Model\ItemsGrouperInterface
     */
    protected $itemsGrouper;

    /**
     * @return \Spryker\Service\Shipment\Model\ItemsGrouperInterface
     */
    protected function getItemsGrouper(): ItemsGrouperInterface
    {
        if ($this->itemsGrouper === null) {
            $this->itemsGrouper = $this->getFactory()->createItemsGrouper();
        }

        return $this->itemsGrouper;
    }

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
}
