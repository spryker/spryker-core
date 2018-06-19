<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Persistence;

use Generated\Shared\Transfer\SpyProductAlternativeStorageEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStoragePersistenceFactory getFactory()
 */
class ProductAlternativeStorageRepository extends AbstractRepository implements ProductAlternativeStorageRepositoryInterface
{
    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\SpyProductAlternativeStorageEntityTransfer
     */
    public function findProductAlternativeStorageEntity($idProduct): SpyProductAlternativeStorageEntityTransfer
    {
        $query = $this->getFactory()
            ->createProductAlternativeStorageQuery()
            ->filterByFkProductAlternative($idProduct);

        return $this->buildQueryFromCriteria($query)->findOneOrCreate();
    }

    /**
     * @param $idProduct
     *
     * @return null|string
     */
    public function findProductSkuById($idProduct)
    {
        $product = $this
            ->getFactory()
            ->getProductQuery()
            ->filterByIdProduct($idProduct)
            ->findOne();

        if (!$product) {
            return null;
        }

        return $product->getSku();
    }

    /**
     * @param $idProduct
     *
     * @return null|array
     */
    public function findAbstractAlternativesIdsByConcreteProductId($idProduct)
    {
        $productAlternativeEntities = $this->getFactory()
            ->getProductAlternativeQuery()
            ->filterByFkProduct($idProduct)
            ->filterByFkProductAbstractAlternative(null, Criteria::ISNOTNULL)
            ->find();

        $alternativesIds = [];

        foreach ($productAlternativeEntities as $alternativeEntity) {
            $alternativesIds[] = $alternativeEntity->getFkProductAbstractAlternative();
        }

        return $alternativesIds;
    }

    /**
     * @param $idProduct
     *
     * @return null|array
     */
    public function findConcreteAlternativesIdsByConcreteProductId($idProduct)
    {
        $productAlternativeEntities = $this->getFactory()
            ->getProductAlternativeQuery()
            ->filterByFkProduct($idProduct)
            ->filterByFkProductConcreteAlternative(null, Criteria::ISNOTNULL)
            ->find();

        $alternativesIds = [];

        foreach ($productAlternativeEntities as $alternativeEntity) {
            $alternativesIds[] = $alternativeEntity->getFkProductConcreteAlternative();
        }

        return $alternativesIds;
    }
}
