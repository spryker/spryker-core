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
use Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToProductFacadeInterface;
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
     * @var \Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeEntityManagerInterface $productAlternativeEntityManager
     * @param \Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeReaderInterface $productAlternativeReader
     * @param \Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToProductFacadeInterface $productFacade
     */
    public function __construct(
        ProductAlternativeEntityManagerInterface $productAlternativeEntityManager,
        ProductAlternativeReaderInterface $productAlternativeReader,
        ProductAlternativeToProductFacadeInterface $productFacade
    ) {
        $this->productAlternativeEntityManager = $productAlternativeEntityManager;
        $this->productAlternativeReader = $productAlternativeReader;
        $this->productFacade = $productFacade;
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
    public function persistProductAlternative(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        $productAlternativeToPersist = $productConcreteTransfer->getProductAlternativeToPersist();

        if (!$productAlternativeToPersist->getIdProduct()) {
            throw new ProductAlternativeHasNoBaseProductException(
                'Unable to create an alternative of nothing. Product id must be set.'
            );
        }

        if (empty($productAlternativeToPersist->getSuggest())) {
            return $productConcreteTransfer;
        }

        $productSuggestionDetails = $this->productFacade
            ->getSuggestionDetails(
                $productAlternativeToPersist->getSuggest()
            );

        if (!$productSuggestionDetails->getIsSuccessful()) {
            return $productConcreteTransfer;
        }

        $idProductAbstractAlternative = $productSuggestionDetails->getIdProductAbstract();
        if ($idProductAbstractAlternative) {
            $existingProductAbstractAlternative = $this->productAlternativeReader
                ->getProductAbstractAlternative(
                    $productConcreteTransfer->getIdProductConcrete(),
                    $idProductAbstractAlternative
                );

            if (!$existingProductAbstractAlternative) {
                $this->createProductAbstractAlternative(
                    $productConcreteTransfer->getIdProductConcrete(),
                    $idProductAbstractAlternative
                );
            }

            return $productConcreteTransfer;
        }

        $idProductConcreteAlternative = $productSuggestionDetails->getIdProductConcrete();
        if ($idProductConcreteAlternative) {
            $existingProductConcreteAlternative = $this->productAlternativeReader
                ->getProductConcreteAlternative(
                    $productConcreteTransfer->getIdProductConcrete(),
                    $idProductConcreteAlternative
                );

            if (!$existingProductConcreteAlternative) {
                $this->createProductConcreteAlternative(
                    $productConcreteTransfer->getIdProductConcrete(),
                    $idProductConcreteAlternative
                );
            }

            return $productConcreteTransfer;
        }

        throw new ProductAlternativeIsNotDefinedException(
            'You must set an id of abstract or concrete product alternative.'
        );
    }

    /**
     * @param int $idProductAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeResponseTransfer
     */
    public function deleteProductAlternativeByIdProductAlternativeResponse(int $idProductAlternative): ProductAlternativeResponseTransfer
    {
        $productAlternativeTransfer = $this->productAlternativeReader
            ->getProductAlternativeByIdProductAlternative($idProductAlternative);

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
}
