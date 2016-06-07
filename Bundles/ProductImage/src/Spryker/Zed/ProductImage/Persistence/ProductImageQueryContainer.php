<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Persistence;

use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageConfigurationPresetTableMap;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageConfigurationPresetValueTableMap;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageTypeTranslationTableMap;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageTypeUsageExclusionTableMap;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageTypeUsageTableMap;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageValuePriceTableMap;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageValueTranslationTableMap;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageValueUsageConstraintTableMap;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageValueUsageTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Tax\Persistence\Map\SpyTaxRateTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductImage\Persistence\ProductImagePersistenceFactory getFactory()
 */
class ProductImageQueryContainer extends AbstractQueryContainer implements ProductImageQueryContainerInterface
{

    /**
     * @param int $idProductImageSet
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImageQuery
     */
    public function queryImagesByIdProductImageSet($idProductImageSet)
    {
        return $this->getFactory()
            ->createProductImageSetToProductImageQuery()
            ->useSpyProductImageQuery()
            ->endUse()
            ->filterByFkProductImageSet($idProductImageSet);
    }

}
