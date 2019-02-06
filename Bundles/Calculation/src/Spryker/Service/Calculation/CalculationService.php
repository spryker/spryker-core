<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Calculation;

use ArrayObject;
use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\Calculation\CalculationServiceFactory getFactory()
 */
class CalculationService extends AbstractService implements CalculationServiceInterface
{
    /**
     * @var \Spryker\Service\Calculation\Items\ItemsGrouperInterface
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
        return $this->getFactory()
            ->createItemsGrouper()
            ->groupByShipment($itemTransfers);
    }
}
