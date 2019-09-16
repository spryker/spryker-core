<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\ProductManagement\Dependency\Facade;

use Spryker\DecimalObject\Decimal;

interface ProductManagementToAvailabilityInterface
{
    /**
     * @param string $sku
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function calculateAvailabilityForProduct(string $sku): Decimal;
}
