<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Tax;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\Tax\Items\ItemHasOwnShipmentTransferChecker;
use Spryker\Service\Tax\Items\ItemHasOwnShipmentTransferCheckerInterface;

class TaxServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\Tax\Items\ItemHasOwnShipmentTransferCheckerInterface
     */
    public function createSplitDeliveryEnabledChecker(): ItemHasOwnShipmentTransferCheckerInterface
    {
        return new ItemHasOwnShipmentTransferChecker();
    }
}
