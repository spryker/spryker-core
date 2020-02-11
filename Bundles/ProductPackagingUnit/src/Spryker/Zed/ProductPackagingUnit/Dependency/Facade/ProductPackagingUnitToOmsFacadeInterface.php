<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Dependency\Facade;

interface ProductPackagingUnitToOmsFacadeInterface
{
    /**
     * @param string $sku
     *
     * @return void
     */
    public function updateReservationQuantity(string $sku): void;
}
