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
     * @return \Orm\Zed\ProductAlternative\Persistence\SpyProductAlternativeQuery
     */
    public function queryProductAlternative(): SpyProductAlternativeQuery
    {
        return SpyProductAlternativeQuery::create();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return \Orm\Zed\ProductAlternative\Persistence\SpyProductAlternativeQuery
     */
    public function queryProductAlternativeByIdProductConcrete(int $idProductConcrete): SpyProductAlternativeQuery
    {
        return $this->queryProductAlternative()
            ->filterByFkProduct($idProductConcrete);
    }

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
        $productAlternativeQuery = $this
            ->getFactory()
            ->createProductAlternativeQuery();

        $productAlternatives = $productAlternativeQuery
            ->filterByFkProduct(
                $idProductConcrete
            )->find();

        $mapper = $this
            ->getFactory()
            ->createProductAlternativeMapper();

        $mappedProductAlternatives = [];

        /** @var \Orm\Zed\ProductAlternative\Persistence\SpyProductAlternative $productAlternative */
        foreach ($productAlternatives as $productAlternative) {
            $mappedProductAlternatives[] = $mapper->mapSpyProductAlternativeEntityToTransfer($productAlternative);
        }

        return $this->hydrateProductAlternativeCollectionWithProductAlternatives($mappedProductAlternatives);
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
    public function getProductAlternativeByIdProductAlternative(int $idProductAlternative): ?ProductAlternativeTransfer
    {
        $productAlternativeQuery = $this
            ->getFactory()
            ->createProductAlternativeQuery();

        $alternativeProduct = $productAlternativeQuery
            ->filterByIdProductAlternative(
                $idProductAlternative
            )->findOne();

        if (!$alternativeProduct) {
            return null;
        }

        return $this->getFactory()
            ->createProductAlternativeMapper()
            ->mapSpyProductAlternativeEntityToTransfer($alternativeProduct);
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
    public function getProductAbstractAlternative(int $idBaseProduct, int $idProductAbstract): ?ProductAlternativeTransfer
    {
        $productAlternativeQuery = $this
            ->getFactory()
            ->createProductAlternativeQuery();

        $alternativeProduct = $productAlternativeQuery
            ->filterByFkProduct($idBaseProduct)
            ->filterByFkProductAbstractAlternative($idProductAbstract)
            ->findOne();

        if (!$alternativeProduct) {
            return null;
        }

        return $this->getFactory()
            ->createProductAlternativeMapper()
            ->mapSpyProductAlternativeEntityToTransfer($alternativeProduct);
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
    public function getProductConcreteAlternative(int $idBaseProduct, int $idProductConcrete): ?ProductAlternativeTransfer
    {
        $productAlternativeQuery = $this
            ->getFactory()
            ->createProductAlternativeQuery();

        $alternativeProduct = $productAlternativeQuery
            ->filterByFkProduct($idBaseProduct)
            ->filterByFkProductConcreteAlternative($idProductConcrete)
            ->findOne();

        if (!$alternativeProduct) {
            return null;
        }

        return $this->getFactory()
            ->createProductAlternativeMapper()
            ->mapSpyProductAlternativeEntityToTransfer($alternativeProduct);
    }

    /**
     * @param array $productAlternatives
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeCollectionTransfer
     */
    protected function hydrateProductAlternativeCollectionWithProductAlternatives(array $productAlternatives): ProductAlternativeCollectionTransfer
    {
        $productAlternativeCollectionTransfer = new ProductAlternativeCollectionTransfer();

        foreach ($productAlternatives as $productAlternative) {
            $productAlternativeCollectionTransfer->addProductAlternative($productAlternative);
        }

        return $productAlternativeCollectionTransfer;
    }
}
