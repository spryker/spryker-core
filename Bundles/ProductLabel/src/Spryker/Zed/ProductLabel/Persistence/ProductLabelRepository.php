<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductLabel\Persistence\ProductLabelPersistenceFactory getFactory()
 */
class ProductLabelRepository extends AbstractRepository implements ProductLabelRepositoryInterface
{
    /**
     * @param int $idProductLabel
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer|null
     */
    public function findProductLabelById(int $idProductLabel): ?ProductLabelTransfer
    {
        $productLabelEntity = $this->getFactory()
            ->createProductLabelQuery()
            ->filterByIdProductLabel($idProductLabel)
            ->findOne();

        if (!$productLabelEntity) {
            return null;
        }

        return $this->getFactory()
            ->createProductLabelMapper()
            ->mapProductLabelEntityToProductLabelTransfer($productLabelEntity, new ProductLabelTransfer());
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractTransfer[]
     */
    public function getProductLabelProductAbstractsByProductAbstractIds(array $productAbstractIds): array
    {
        $productLabelProductAbstractEntities = $this->getFactory()
            ->createProductRelationQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->joinWithSpyProductLabel()
            ->orderBy(SpyProductLabelTableMap::COL_POSITION)
            ->find();

        if (!$productLabelProductAbstractEntities->count()) {
            return [];
        }

        return $this->getFactory()
            ->createProductLabelProductAbstractMapper()
            ->mapProductLabelProductAbstractEntitiesToProductLabelProductTransfers($productLabelProductAbstractEntities, []);
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractTransfer[]
     */
    public function getProductLabelProductAbstractsByFilter(FilterTransfer $filterTransfer): array
    {
        $productLabelProductAbstractEntities = $this->getFactory()
            ->createProductRelationQuery()
            ->setLimit($filterTransfer->getLimit())
            ->setOffset($filterTransfer->getOffset())
            ->find();

        if (!$productLabelProductAbstractEntities->count()) {
            return [];
        }

        return $this->getFactory()
            ->createProductLabelProductAbstractMapper()
            ->mapProductLabelProductAbstractEntitiesToProductLabelProductTransfers($productLabelProductAbstractEntities, []);
    }

    /**
     * @module Locale
     *
     * @return \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer[]
     */
    public function getProductLabelLocalizedAttributes(): array
    {
        $productLabelLocalizedAttributesEntities = $this->getFactory()
            ->createLocalizedAttributesQuery()
            ->joinWithSpyProductLabel()
            ->joinWithSpyLocale()
            ->find();

        if (!$productLabelLocalizedAttributesEntities->count()) {
            return [];
        }

        return $this->getFactory()
            ->createProductLabelLocalizedAttributesMapper()
            ->mapProductLabelLocalizedAttributesEntitiesToProductLabelLocalizedAttributesTransfers(
                $productLabelLocalizedAttributesEntities,
                []
            );
    }
}
