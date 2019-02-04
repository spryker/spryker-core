<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Calculation;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\Calculation\Items\ItemsGrouper;
use Spryker\Service\Calculation\Items\ItemsGrouperInterface;

class CalculationServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\Calculation\Items\ItemsGrouperInterface
     */
    public function createItemsGrouper(): ItemsGrouperInterface
    {
        return new ItemsGrouper();
    }
}
