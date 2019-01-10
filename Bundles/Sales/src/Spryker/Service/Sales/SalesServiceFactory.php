<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Sales;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\Sales\Items\ItemHasOwnShipmentTransferChecker;
use Spryker\Service\Sales\Items\ItemHasOwnShipmentTransferCheckerInterface;

class SalesServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\Sales\Items\ItemHasOwnShipmentTransferCheckerInterface
     */
    public function createSplitDeliveryEnabledChecker(): ItemHasOwnShipmentTransferCheckerInterface
    {
        return new ItemHasOwnShipmentTransferChecker();
    }
}
