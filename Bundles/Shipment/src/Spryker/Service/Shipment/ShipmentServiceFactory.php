<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Shipment;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\Shipment\Items\ItemsGrouper;
use Spryker\Service\Shipment\Items\ItemsGrouperInterface;

class ShipmentServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\Shipment\Items\ItemsGrouperInterface
     */
    public function createItemsGrouper(): ItemsGrouperInterface
    {
        return new ItemsGrouper();
    }
}
