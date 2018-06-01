<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Business\ProductAlternative;

use Generated\Shared\Transfer\ProductAlternativeTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductAlternative\Business\Exception\ProductAlternativeHasNoBaseProductException;
use Spryker\Zed\ProductAlternative\Business\Exception\ProductAlternativeIsNotDefinedException;
use Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeEntityManagerInterface;

class ProductAlternativeWriter implements ProductAlternativeWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeEntityManagerInterface
     */
    protected $productAlternativeEntityManager;

    /**
     * @var \Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeReaderInterface
     */
    protected $productAlternativeReader;

    /**
     * @param \Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeEntityManagerInterface $productAlternativeEntityManager
     * @param \Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeReaderInterface $productAlternativeReader
     */
    public function __construct(
        ProductAlternativeEntityManagerInterface $productAlternativeEntityManager,
        ProductAlternativeReaderInterface $productAlternativeReader
    ) {
        $this->productAlternativeEntityManager = $productAlternativeEntityManager;
        $this->productAlternativeReader = $productAlternativeReader;
    }

    /**
     * @param int $idProduct
     * @param int $idProductAbstractAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function createProductAbstractAlternative(int $idProduct, int $idProductAbstractAlternative): ProductAlternativeTransfer
    {
        return $this->productAlternativeEntityManager->createProductAbstractAlternative($idProduct, $idProductAbstractAlternative);
    }

    /**
     * @param int $idProduct
     * @param int $idProductConcreteAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function createProductConcreteAlternative(int $idProduct, int $idProductConcreteAlternative): ProductAlternativeTransfer
    {
        return $this->productAlternativeEntityManager->createProductConcreteAlternative($idProduct, $idProductConcreteAlternative);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @throws \Spryker\Zed\ProductAlternative\Business\Exception\ProductAlternativeHasNoBaseProductException
     * @throws \Spryker\Zed\ProductAlternative\Business\Exception\ProductAlternativeIsNotDefinedException
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function persistProductAlternatives(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        /** @var \Generated\Shared\Transfer\ProductAlternativeTransfer $productAlternative */
        foreach ($productConcreteTransfer->getProductAlternatives() as $productAlternative) {
            $idProductAlternative = $productAlternative->getIdProductAlternative();

            if (!$idProductAlternative) {
                if (!$productAlternative->getIdProduct()) {
                    throw new ProductAlternativeHasNoBaseProductException(
                        'Unable to create an alternative of nothing. Base id product must be set.'
                    );
                }

                if ($productAlternative->getIdProductConcreteAlternative()) {
                    $this->createProductConcreteAlternative(
                        $productConcreteTransfer->getIdProductConcrete(),
                        $productAlternative->getIdProductConcreteAlternative()
                    );

                    return $productConcreteTransfer;
                }

                if ($productAlternative->getIdProductAbstractAlternative()) {
                    $this->createProductAbstractAlternative(
                        $productConcreteTransfer->getIdProductConcrete(),
                        $productAlternative->getIdProductAbstractAlternative()
                    );

                    return $productConcreteTransfer;
                }

                throw new ProductAlternativeIsNotDefinedException(
                    'You must set an id of abstract or concrete product alternative.'
                );
            }

            $this->updateProductAlternative($productAlternative);
        }

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeTransfer $productAlternativeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    protected function updateProductAlternative(ProductAlternativeTransfer $productAlternativeTransfer): ProductAlternativeTransfer
    {
        return $this->productAlternativeEntityManager->updateProductAlternative($productAlternativeTransfer);
    }
}
