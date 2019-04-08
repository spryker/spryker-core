<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductStorage\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\TaxProductStorage\Persistence\TaxProductStoragePersistenceFactory getFactory()
 */
class TaxProductStorageRepository extends AbstractRepository implements TaxProductStorageRepositoryInterface
{
    /**
     * @module Product
     *
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract[]
     */
    public function findProductAbstractEntitiesByProductAbstractIds(array $productAbstractIds): array
    {
        if (count($productAbstractIds) === 0) {
            return [];
        }

        $query = $this->getFactory()
           ->getProductAbstractQuery()
           ->filterByIdProductAbstract_In($productAbstractIds);

        return $query->find()->getArrayCopy();
    }

    /**
     * @param int[] $productAbstractIds
     * @param string|null $keyColumn
     *
     * @return \Orm\Zed\TaxProductStorage\Persistence\SpyTaxProductStorage[]
     */
    public function findTaxProductStorageEntities(array $productAbstractIds, ?string $keyColumn = null): array
    {
        if (count($productAbstractIds) === 0) {
            return [];
        }

        $query = $this->getFactory()
            ->createTaxProductStorageQuery()
            ->filterByFkProductAbstract_In($productAbstractIds);

        return $query->find()
            ->getArrayCopy($keyColumn);
    }

    /**
     * @return \Orm\Zed\TaxProductStorage\Persistence\SpyTaxProductStorage[]
     */
    public function findAllTaxProductStorageEntities(): array
    {
        $query = $this->getFactory()
            ->createTaxProductStorageQuery();

        return $query->find()->getArrayCopy();
    }
}
