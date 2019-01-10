<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Shipment;

use Spryker\Service\Kernel\AbstractService;
use \ArrayObject;
use Spryker\Service\Shipment\Model\ItemsGroupperInterface;

/**
 * @method \Spryker\Service\Shipment\ShipmentServiceFactory getFactory()
 */
class ShipmentService extends AbstractService implements ShipmentServiceInterface
{
    /**
     * @var ItemsGroupperInterface
     */
    protected $itemsGroupper;

    /**
     * @param ItemsGroupperInterface $itemsGroupper
     */
    public function __construct(ItemsGroupperInterface $itemsGroupper)
    {
        $this->itemsGroupper = $itemsGroupper;
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
        return $this->itemsGroupper->groupByShipment($itemTransfers);
    }
}
