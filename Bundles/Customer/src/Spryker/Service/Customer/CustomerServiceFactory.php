<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Customer;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\Customer\Items\ItemHasOwnShipmentTransferChecker;
use Spryker\Service\Customer\Items\ItemHasOwnShipmentTransferCheckerInterface;

class CustomerServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\Customer\Items\ItemHasOwnShipmentTransferCheckerInterface
     */
    public function createSplitDeliveryEnabledChecker(): ItemHasOwnShipmentTransferCheckerInterface
    {
        return new ItemHasOwnShipmentTransferChecker();
    }
}
