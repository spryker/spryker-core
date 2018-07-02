<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPackagingUnit;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductPackagingUnit\Expander\ProductPackagingUnitAmountExpander;
use Spryker\Client\ProductPackagingUnit\Expander\ProductPackagingUnitAmountExpanderInterface;

class ProductPackagingUnitFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductPackagingUnit\Expander\ProductPackagingUnitAmountExpanderInterface
     */
    public function createProductPackagingUnitAmountExpander(): ProductPackagingUnitAmountExpanderInterface
    {
        return new ProductPackagingUnitAmountExpander();
    }
}
