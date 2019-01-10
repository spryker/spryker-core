<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Shipment;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\Shipment\Model\ItemsGrouper;
use Spryker\Service\Shipment\Model\ItemsGrouperInterface;

class ShipmentServiceFactory extends AbstractServiceFactory
{
    /**
     * @return ItemsGrouperInterface
     */
    public function createItemsGrouper(): ItemsGrouperInterface
    {
        return new ItemsGrouper();
    }
}
