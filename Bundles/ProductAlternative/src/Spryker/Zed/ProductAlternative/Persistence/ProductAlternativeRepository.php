<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Persistence;

use Generated\Shared\Transfer\ProductAlternativeCollectionTransfer;
use Generated\Shared\Transfer\ProductAlternativeTransfer;
use Orm\Zed\ProductAlternative\Persistence\SpyProductAlternativeQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductAlternative\Persistence\ProductAlternativePersistenceFactory getFactory()
 */
class ProductAlternativeRepository extends AbstractRepository implements ProductAlternativeRepositoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeCollectionTransfer
     */
    public function getProductAlternativesForProductConcrete(int $idProductConcrete): ProductAlternativeCollectionTransfer
    {
        $productAlternativeQuery = $this->getFactory()
            ->createProductAlternativeQuery()
            ->filterByFkProduct($idProductConcrete);

        $productAlternatives = $this->buildQueryFromCriteria($productAlternativeQuery)
            ->find();

        return $this->hydrateProductAlternativeCollectionWithProductAlternatives($productAlternatives);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductAlternative
     *
     * @return null|\Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function findProductAlternativeByIdProductAlternative(int $idProductAlternative): ?ProductAlternativeTransfer
    {
        $productAlternativeQuery = $this->getFactory()
            ->createProductAlternativeQuery()
            ->filterByIdProductAlternative($idProductAlternative);

        $alternativeProduct = $this->buildQueryFromCriteria($productAlternativeQuery)
            ->findOne();

        if (!$alternativeProduct) {
            return null;
        }

        return $this->getFactory()
            ->createProductAlternativeMapper()
            ->mapSpyProductAlternativeEntityTransferToTransfer($alternativeProduct);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idBaseProduct
     * @param int $idProductAbstract
     *
     * @return null|\Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function findProductAbstractAlternative(int $idBaseProduct, int $idProductAbstract): ?ProductAlternativeTransfer
    {
        $productAlternativeQuery = $this->getFactory()
            ->createProductAlternativeQuery()
            ->filterByFkProduct($idBaseProduct)
            ->filterByFkProductAbstractAlternative($idProductAbstract);

        $alternativeProduct = $this->buildQueryFromCriteria($productAlternativeQuery)
            ->findOne();

        if (!$alternativeProduct) {
            return null;
        }

        return $this->getFactory()
            ->createProductAlternativeMapper()
            ->mapSpyProductAlternativeEntityTransferToTransfer($alternativeProduct);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idBaseProduct
     * @param int $idProductConcrete
     *
     * @return null|\Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function findProductConcreteAlternative(int $idBaseProduct, int $idProductConcrete): ?ProductAlternativeTransfer
    {
        $productAlternativeQuery = $this->getFactory()
            ->createProductAlternativeQuery()
            ->filterByFkProduct($idBaseProduct)
            ->filterByFkProductConcreteAlternative($idProductConcrete);

        $alternativeProduct = $this->buildQueryFromCriteria($productAlternativeQuery)
            ->findOne();

        if (!$alternativeProduct) {
            return null;
        }

        return $this->getFactory()
            ->createProductAlternativeMapper()
            ->mapSpyProductAlternativeEntityTransferToTransfer($alternativeProduct);
    }

    /**
     * @param array $productAlternatives
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeCollectionTransfer
     */
    protected function hydrateProductAlternativeCollectionWithProductAlternatives(array $productAlternatives): ProductAlternativeCollectionTransfer
    {
        $mapper = $this->getFactory()
            ->createProductAlternativeMapper();

        $productAlternativeCollectionTransfer = new ProductAlternativeCollectionTransfer();

        foreach ($productAlternatives as $productAlternative) {
            $productAlternativeCollectionTransfer->addProductAlternative(
                $mapper->mapSpyProductAlternativeEntityTransferToTransfer($productAlternative)
            );
        }

        return $productAlternativeCollectionTransfer;
    }

    /**
     * @return \Orm\Zed\ProductAlternative\Persistence\SpyProductAlternativeQuery
     */
    protected function queryProductAlternative(): SpyProductAlternativeQuery
    {
        return SpyProductAlternativeQuery::create();
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Orm\Zed\ProductAlternative\Persistence\SpyProductAlternativeQuery
     */
    protected function queryProductAlternativeByIdProductConcrete(int $idProductConcrete): SpyProductAlternativeQuery
    {
        return $this->queryProductAlternative()
            ->filterByFkProduct($idProductConcrete);
    }
}
