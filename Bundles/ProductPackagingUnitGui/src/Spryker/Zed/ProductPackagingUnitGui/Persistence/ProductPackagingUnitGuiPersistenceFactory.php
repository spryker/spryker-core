<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitGui\Persistence;

use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitTypeQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductPackagingUnitGui\ProductPackagingUnitGuiDependencyProvider;

/**
 * @method \Spryker\Zed\ProductPackagingUnitGui\ProductPackagingUnitGuiConfig getConfig()
 * @method \Spryker\Zed\ProductPackagingUnitGui\Persistence\ProductPackagingUnitGuiRepositoryInterface getRepository()
 */
class ProductPackagingUnitGuiPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitTypeQuery
     */
    public function getProductPackagingUnitTypePropelQuery(): SpyProductPackagingUnitTypeQuery
    {
        return $this->getProvidedDependency(ProductPackagingUnitGuiDependencyProvider::PROPEL_QUERY_PRODUCT_PACKAGING_UNIT_TYPE);
    }
}
