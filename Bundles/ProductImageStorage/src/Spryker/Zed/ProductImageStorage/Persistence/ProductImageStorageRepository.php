<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageStorage\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductImageStorage\Persistence\ProductImageStoragePersistenceFactory getFactory()
 */
class ProductImageStorageRepository extends AbstractRepository implements ProductImageStorageRepositoryInterface
{
    /**
     * @param array $idsProduct
     *
     * @return array
     */
    public function getProductLocalizedAttributesWithProductByIdProductIn(array $idsProduct): array
    {
        return $this->getFactory()
            ->getProductLocalizedAttributesQuery()
            ->select([
                SpyProductLocalizedAttributesTableMap::COL_ID_PRODUCT_ATTRIBUTES,
                SpyProductLocalizedAttributesTableMap::COL_FK_PRODUCT,
                SpyProductLocalizedAttributesTableMap::COL_FK_LOCALE,
                SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT,
            ])
            ->innerJoinWithSpyProduct()
            ->filterByFkProduct_In($idsProduct)
            ->find()
            ->toArray();
    }

    /**
     * @param array $fksProduct
     * @param array $fksProductAbstract
     *
     * @return \Generated\Shared\Transfer\SpyProductImageSetEntityTransfer[]
     */
    public function getProductImageSetsByFkProductInOrFkAbstractProductIn(array $fksProduct, array $fksProductAbstract): array
    {
        $productImageSetsQuery = $this->getFactory()
            ->getProductImageSetQuery()
            ->innerJoinWithSpyLocale()
            ->innerJoinWithSpyProductImageSetToProductImage()
            ->useSpyProductImageSetToProductImageQuery()
                ->innerJoinWithSpyProductImage()
            ->endUse()
            ->filterByFkProduct_In($fksProduct)
            ->_or()
            ->filterByFkProductAbstract_In($fksProductAbstract);

        return $this->buildQueryFromCriteria($productImageSetsQuery)->find();
    }
}
