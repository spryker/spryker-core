<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Dependency\Facade;

interface ProductManagementToStockInterface
{
    /**
     * @return array
     */
    public function getWarehouseToStoreMapping();
}
