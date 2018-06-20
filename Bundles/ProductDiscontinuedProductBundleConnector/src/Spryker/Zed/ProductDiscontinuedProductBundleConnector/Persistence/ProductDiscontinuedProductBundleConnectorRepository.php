<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedProductBundleConnector\Persistence;

use Generated\Shared\Transfer\ProductDiscontinuedTransfer;
use Orm\Zed\ProductBundle\Persistence\Map\SpyProductBundleTableMap;
use Orm\Zed\ProductDiscontinued\Persistence\Map\SpyProductDiscontinuedTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\ProductDiscontinuedProductBundleConnector\Persistence\ProductDiscontinuedProductBundleConnectorPersistenceFactory getFactory()
 */
class ProductDiscontinuedProductBundleConnectorRepository extends AbstractRepository implements ProductDiscontinuedProductBundleConnectorRepositoryInterface
{
    /**
     * @module ProductBundle
     *
     * @param int $idProductDiscontinue
     *
     * @return int[]
     */
    public function findRelatedBundleProductsIds(int $idProductDiscontinue): array
    {
        $productIds = $this->getFactory()
            ->createProductDiscontinuedQuery()
            ->filterByIdProductDiscontinued($idProductDiscontinue)
            ->addJoin(
                SpyProductDiscontinuedTableMap::COL_FK_PRODUCT,
                SpyProductBundleTableMap::COL_FK_BUNDLED_PRODUCT,
                Criteria::LEFT_JOIN
            )
            ->addAsColumn(ProductDiscontinuedTransfer::FK_PRODUCT, SpyProductBundleTableMap::COL_FK_PRODUCT)
            ->select([ProductDiscontinuedTransfer::FK_PRODUCT])
            ->find()
            ->toArray();

        if ($productIds[0] === null) {
            return [];
        }

        return $productIds;
    }
}
