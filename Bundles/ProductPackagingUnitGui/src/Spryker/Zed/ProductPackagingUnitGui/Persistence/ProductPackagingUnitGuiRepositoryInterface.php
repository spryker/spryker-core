<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitGui\Persistence;

use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitTypeQuery;

interface ProductPackagingUnitGuiRepositoryInterface
{
    /**
     * @return \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitTypeQuery
     */
    public function queryProductPackagingUnitTypes(): SpyProductPackagingUnitTypeQuery;
}
