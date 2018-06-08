<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Business\ProductAlternative;

use Generated\Shared\Transfer\ProductAlternativeResponseTransfer;
use Generated\Shared\Transfer\ProductAlternativeTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ResponseMessageTransfer;
use Spryker\Zed\ProductAlternative\Business\Exception\ProductAlternativeHasNoBaseProductException;
use Spryker\Zed\ProductAlternative\Business\Exception\ProductAlternativeIsNotDefinedException;
use Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeEntityManagerInterface;

class ProductAlternativeWriter implements ProductAlternativeWriterInterface
{
    protected const STATUS_REMOVE_PRODUCT_ALTERNATIVE_SUCCESS = 'Product alternative with id = %d successfully removed.';
    protected const STATUS_REMOVE_PRODUCT_ALTERNATIVE_ERROR = 'Unable to remove product alternative: Product alternative was not found.';

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
     * @return \Generated\Shared\Transfer\ProductAlternativeResponseTransfer
     */
    public function createProductAbstractAlternativeResponse(int $idProduct, int $idProductAbstractAlternative): ProductAlternativeResponseTransfer
    {
        return (new ProductAlternativeResponseTransfer())
            ->setProductAlternative(
                $this->createProductAbstractAlternative($idProduct, $idProductAbstractAlternative)
            )->setIsSuccessful(true);
    }

    /**
     * @param int $idProduct
     * @param int $idProductAbstractAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeResponseTransfer
     */
    public function createProductConcreteAlternativeResponse(int $idProduct, int $idProductAbstractAlternative): ProductAlternativeResponseTransfer
    {
        return (new ProductAlternativeResponseTransfer())
            ->setProductAlternative(
                $this->createProductConcreteAlternative($idProduct, $idProductAbstractAlternative)
            )->setIsSuccessful(true);
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
     * @param int $idProductAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeResponseTransfer
     */
    public function deleteProductAlternativeByIdProductAlternativeResponse(int $idProductAlternative): ProductAlternativeResponseTransfer
    {
        $productAlternative = $this->productAlternativeReader
            ->getProductAlternativeByIdProductAlternative($idProductAlternative);

        return $this->handleProductAlternativeDeletion($productAlternative);
    }

    /**
     * @param int $idProduct
     * @param int $idProductAbstractAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    protected function createProductAbstractAlternative(int $idProduct, int $idProductAbstractAlternative): ProductAlternativeTransfer
    {
        return $this->productAlternativeEntityManager
            ->createProductAbstractAlternative(
                $idProduct,
                $idProductAbstractAlternative
            );
    }

    /**
     * @param int $idProduct
     * @param int $idProductConcreteAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    protected function createProductConcreteAlternative(int $idProduct, int $idProductConcreteAlternative): ProductAlternativeTransfer
    {
        return $this->productAlternativeEntityManager
            ->createProductConcreteAlternative(
                $idProduct,
                $idProductConcreteAlternative
            );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeTransfer $productAlternativeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    protected function updateProductAlternative(ProductAlternativeTransfer $productAlternativeTransfer): ProductAlternativeTransfer
    {
        return $this->productAlternativeEntityManager
            ->updateProductAlternative($productAlternativeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeTransfer|null $productAlternativeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeResponseTransfer
     */
    protected function handleProductAlternativeDeletion(?ProductAlternativeTransfer $productAlternativeTransfer): ProductAlternativeResponseTransfer
    {
        $productAlternativeResponseTransfer = (new ProductAlternativeResponseTransfer())
            ->setProductAlternative($productAlternativeTransfer);

        $responseMessageTransfer = new ResponseMessageTransfer();

        if ($productAlternativeTransfer) {
            $idProductAlternative = $productAlternativeTransfer
                ->getIdProductAlternative();

            $this->productAlternativeEntityManager
                ->deleteProductAlternative($productAlternativeTransfer);

            $responseMessageTransfer
                ->setText(
                    sprintf(static::STATUS_REMOVE_PRODUCT_ALTERNATIVE_SUCCESS, $idProductAlternative)
                );

            return $productAlternativeResponseTransfer
                ->setIsSuccessful(true)
                ->addMessage($responseMessageTransfer);
        }

        $responseMessageTransfer
            ->setText(
                static::STATUS_REMOVE_PRODUCT_ALTERNATIVE_ERROR
            );

        return $productAlternativeResponseTransfer
            ->setIsSuccessful(false)
            ->addMessage($responseMessageTransfer);
    }
}
