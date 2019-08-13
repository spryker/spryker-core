<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Persistence;

use Generated\Shared\Transfer\ProductAbstractOptionGroupStatusTransfer;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductAbstractProductOptionGroupTableMap;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionGroupTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionPersistenceFactory getFactory()
 */
class ProductOptionRepository extends AbstractRepository implements ProductOptionRepositoryInterface
{
    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionGroupStatusTransfer[]
     */
    public function getProductAbstractOptionGroupStatusesByProductAbstractIds(array $productAbstractIds): array
    {
        $productAbstractOptionGroupStatuses = $this->getFactory()
            ->createProductAbstractProductOptionGroupQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->joinSpyProductOptionGroup()
            ->select([
                ProductAbstractOptionGroupStatusTransfer::ID_PRODUCT_ABSTRACT,
                ProductAbstractOptionGroupStatusTransfer::IS_ACTIVE,
                ProductAbstractOptionGroupStatusTransfer::PRODUCT_OPTION_GROUP_NAME,
            ])
            ->withColumn(SpyProductAbstractProductOptionGroupTableMap::COL_FK_PRODUCT_ABSTRACT, ProductAbstractOptionGroupStatusTransfer::ID_PRODUCT_ABSTRACT)
            ->withColumn(SpyProductOptionGroupTableMap::COL_ACTIVE, ProductAbstractOptionGroupStatusTransfer::IS_ACTIVE)
            ->withColumn(SpyProductOptionGroupTableMap::COL_NAME, ProductAbstractOptionGroupStatusTransfer::PRODUCT_OPTION_GROUP_NAME)
            ->find()
            ->toArray();

        return $this->getFactory()
            ->createProductOptionMapper()
            ->mapProductAbstractOptionGroupStatusesToTransfers($productAbstractOptionGroupStatuses);
    }
}
