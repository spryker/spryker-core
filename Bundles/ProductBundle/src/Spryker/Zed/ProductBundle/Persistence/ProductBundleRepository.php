<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Persistence;

use Generated\Shared\Transfer\ProductBundleCollectionTransfer;
use Generated\Shared\Transfer\ProductBundleCriteriaFilterTransfer;
use Orm\Zed\ProductBundle\Persistence\Base\SpyProductBundleQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductBundle\Persistence\ProductBundlePersistenceFactory getFactory()
 */
class ProductBundleRepository extends AbstractRepository implements ProductBundleRepositoryInterface
{
    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductForBundleTransfer[]
     */
    public function findBundledProductsBySku(string $sku): array
    {
        $productBundleEntities = $this->getFactory()
            ->createProductBundleQuery()
            ->joinWithSpyProductRelatedByFkProduct()
            ->useSpyProductRelatedByFkProductQuery()
                ->filterBySku($sku)
            ->endUse()
            ->find()
            ->getData();

        return $this->getFactory()
            ->createProductBundleMapper()
            ->mapProductBundleEntitiesToProductForBundleTransfers($productBundleEntities);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductBundleCriteriaFilterTransfer $productBundleCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductBundleCollectionTransfer
     */
    public function getProductBundleCollectionByCriteriaFilter(ProductBundleCriteriaFilterTransfer $productBundleCriteriaFilterTransfer): ProductBundleCollectionTransfer
    {
        $productBundleQuery = $this->getFactory()
            ->createProductBundleQuery()
            ->joinWithSpyProductRelatedByFkBundledProduct();

        $productBundleQuery = $this->setProductBundleQueryFilters($productBundleQuery, $productBundleCriteriaFilterTransfer);
        $productBundleEntities = $productBundleQuery->find();

        return $this->getFactory()
            ->createProductBundleMapper()
            ->mapProductBundleEntitiesToProductBundleCollectionTransfer($productBundleEntities->getArrayCopy(), new ProductBundleCollectionTransfer());
    }

    /**
     * @param \Orm\Zed\ProductBundle\Persistence\Base\SpyProductBundleQuery $productBundleQuery
     * @param \Generated\Shared\Transfer\ProductBundleCriteriaFilterTransfer $productBundleCriteriaFilterTransfer
     *
     * @return \Orm\Zed\ProductBundle\Persistence\Base\SpyProductBundleQuery
     */
    protected function setProductBundleQueryFilters(
        SpyProductBundleQuery $productBundleQuery,
        ProductBundleCriteriaFilterTransfer $productBundleCriteriaFilterTransfer
    ): SpyProductBundleQuery {
        if ($productBundleCriteriaFilterTransfer->getIdProductConcrete()) {
            $productBundleQuery->filterByFkProduct($productBundleCriteriaFilterTransfer->getIdProductConcrete());
        }

        if ($productBundleCriteriaFilterTransfer->getIdBundledProduct()) {
            $productBundleQuery->filterByFkBundledProduct($productBundleCriteriaFilterTransfer->getIdBundledProduct());
        }

        return $productBundleQuery;
    }
}
