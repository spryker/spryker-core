<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Business\ProductAlternative;

use Generated\Shared\Transfer\ProductAlternativeCollectionTransfer;
use Generated\Shared\Transfer\ProductAlternativeTransfer;
use Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeRepositoryInterface;

class ProductAlternativeReader implements ProductAlternativeReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeRepositoryInterface
     */
    protected $productAlternativeRepository;

    /**
     * @param \Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeRepositoryInterface $productAlternativeRepository
     */
    public function __construct(ProductAlternativeRepositoryInterface $productAlternativeRepository)
    {
        $this->productAlternativeRepository = $productAlternativeRepository;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeCollectionTransfer
     */
    public function getProductAlternativesByIdProductConcrete(int $idProductConcrete): ProductAlternativeCollectionTransfer
    {
        return $this->productAlternativeRepository
            ->getProductAlternativesForProductConcrete($idProductConcrete);
    }

    /**
     * @param int $idProductAlternative
     *
     * @return null|\Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function getProductAlternativeByIdProductAlternative(int $idProductAlternative): ?ProductAlternativeTransfer
    {
        return $this->productAlternativeRepository
            ->findProductAlternativeByIdProductAlternative($idProductAlternative);
    }

    /**
     * @param int $idBaseProduct
     * @param int $idProductAbstract
     *
     * @return null|\Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function getProductAbstractAlternative(int $idBaseProduct, int $idProductAbstract): ?ProductAlternativeTransfer
    {
        return $this->productAlternativeRepository
            ->findProductAbstractAlternative($idBaseProduct, $idProductAbstract);
    }

    /**
     * @param int $idBaseProduct
     * @param int $idProductConcrete
     *
     * @return null|\Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function getProductConcreteAlternative(int $idBaseProduct, int $idProductConcrete): ?ProductAlternativeTransfer
    {
        return $this->productAlternativeRepository
            ->findProductConcreteAlternative($idBaseProduct, $idProductConcrete);
    }
}
