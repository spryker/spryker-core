<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Sales;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\Sales\Model\SplitDeliveryEnabledChecker;
use Spryker\Service\Sales\Model\SplitDeliveryEnabledCheckerInterface;

class SalesServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\Sales\Model\SplitDeliveryEnabledCheckerInterface
     */
    public function createSplitDeliveryEnabledChecker(): SplitDeliveryEnabledCheckerInterface
    {
        return new SplitDeliveryEnabledChecker();
    }
}
