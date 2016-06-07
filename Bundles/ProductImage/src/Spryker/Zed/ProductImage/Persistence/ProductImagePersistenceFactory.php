<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Persistence;

use Orm\Zed\ProductImage\Persistence\SpyProductImageConfigurationPresetQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageConfigurationPresetValueQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImageQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageTypeQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageTypeTranslationQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageTypeUsageExclusionQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageTypeUsageQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageValueQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageValueTranslationQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageValueUsageConstraintQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageValueUsageQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Tax\Persistence\SpyTaxSetQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\ProductImage\ProductImageConfig getConfig()
 * @method \Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainer getQueryContainer()
 */
class ProductImagePersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImageQuery
     */
    public function createProductImageSetToProductImageQuery()
    {
        return SpyProductImageSetToProductImageQuery::create();
    }

}
