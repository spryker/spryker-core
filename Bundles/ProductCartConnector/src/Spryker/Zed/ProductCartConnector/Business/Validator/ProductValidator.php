<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCartConnector\Business\Validator;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToProductInterface;

class ProductValidator implements ProductValidatorInterface
{
    public const MESSAGE_ERROR_ABSTRACT_PRODUCT_EXISTS = 'product-cart.validation.error.abstract-product-exists';
    public const MESSAGE_ERROR_CONCRETE_PRODUCT_EXISTS = 'product-cart.validation.error.concrete-product-exists';
    public const MESSAGE_PARAM_SKU = 'sku';
    public const MESSAGE_ERROR_CONCRETE_PRODUCT_INACTIVE = 'product-cart.validation.error.concrete-product-inactive';

    protected const SKU_CONCRETE = 'SKU_CONCRETE';
    protected const SKU_ABSTRACT = 'SKU_ABSTRACT';

    /**
     * @var \Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToProductInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToProductInterface $productFacade
     */
    public function __construct(ProductCartConnectorToProductInterface $productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateItems(CartChangeTransfer $cartChangeTransfer)
    {
        $responseTransfer = new CartPreCheckResponseTransfer();

        $skus = $this->getProductSkusFromCartChangeTransfer($cartChangeTransfer);
        $indexedProductConcreteTransfers = $this->getIndexedProductConcretesByProductConcreteSkus($skus[static::SKU_CONCRETE]);
        $indexedProductAbstractTransfers = $this->getIndexedProductAbstractsByProductAbstractSkus($skus[static::SKU_ABSTRACT]);

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getSku()) {
                if ($this->validateConcreteItem($itemTransfer, $responseTransfer, $indexedProductConcreteTransfers) === false) {
                    continue;
                }
                $this->productStatusCheck($indexedProductConcreteTransfers[$itemTransfer->getSku()], $responseTransfer);

                continue;
            }

            $this->validateAbstractItem($itemTransfer, $responseTransfer, $indexedProductAbstractTransfers);
        }

        return $this->setResponseSuccessful($responseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $responseTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $indexedProductConcreteTransfers
     *
     * @return bool
     */
    protected function validateConcreteItem(
        ItemTransfer $itemTransfer,
        CartPreCheckResponseTransfer $responseTransfer,
        array $indexedProductConcreteTransfers
    ): bool {
        if (isset($indexedProductConcreteTransfers[$itemTransfer->getSku()])) {
            return true;
        }

        $message = $this->createViolationMessage(static::MESSAGE_ERROR_CONCRETE_PRODUCT_EXISTS);
        $message->setParameters([
            static::MESSAGE_PARAM_SKU => $itemTransfer->getSku(),
        ]);

        $responseTransfer->addMessage($message);

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function productStatusCheck(ProductConcreteTransfer $productConcreteTransfer, CartPreCheckResponseTransfer $responseTransfer): void
    {
        if ($productConcreteTransfer->getIsActive()) {
            return;
        }

        $responseTransfer->addMessage($this->createItemInactiveErrorMessage($productConcreteTransfer->getSku()));
    }

    /**
     * @param string $concreteSku
     *
     * @return bool
     */
    protected function isProductConcreteActive(string $concreteSku): bool
    {
        return $this->productFacade->isProductConcreteActive(
            (new ProductConcreteTransfer())->setSku($concreteSku)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $responseTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer[] $indexedProductAbstractTransfers
     *
     * @return void
     */
    protected function validateAbstractItem(
        ItemTransfer $itemTransfer,
        CartPreCheckResponseTransfer $responseTransfer,
        array $indexedProductAbstractTransfers
    ) {
        if (isset($indexedProductAbstractTransfers[$itemTransfer->getAbstractSku()])) {
            return;
        }

        $message = $this->createViolationMessage(static::MESSAGE_ERROR_CONCRETE_PRODUCT_EXISTS);
        $message->setParameters([
            static::MESSAGE_PARAM_SKU => $itemTransfer->getAbstractSku(),
        ]);

        $responseTransfer->addMessage($message);
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createItemInactiveErrorMessage(string $sku): MessageTransfer
    {
        return (new MessageTransfer())
            ->setValue(static::MESSAGE_ERROR_CONCRETE_PRODUCT_INACTIVE)
            ->setParameters([
                static::MESSAGE_PARAM_SKU => $sku,
            ]);
    }

    /**
     * @param string $translationKey
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createViolationMessage($translationKey)
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue($translationKey);

        return $messageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $responseTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    protected function setResponseSuccessful(CartPreCheckResponseTransfer $responseTransfer)
    {
        $isSuccessful = count($responseTransfer->getMessages()) === 0;
        $responseTransfer->setIsSuccess($isSuccessful);

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return string[][]
     */
    protected function getProductSkusFromCartChangeTransfer(CartChangeTransfer $cartChangeTransfer): array
    {
        $skus = [
            static::SKU_CONCRETE => [],
            static::SKU_ABSTRACT => [],
        ];
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getSku()) {
                $skus[static::SKU_CONCRETE][] = $itemTransfer->getSku();

                continue;
            }

            $skus[static::SKU_ABSTRACT][] = $itemTransfer->getAbstractSku();
        }

        return $skus;
    }

    /**
     * @param string[] $skus
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    protected function getIndexedProductConcretesByProductConcreteSkus(array $skus): array
    {
        if (!$skus) {
            return [];
        }

        $productConcreteTransfers = $this->productFacade->getRawProductConcreteTransfersByConcreteSkus($skus);
        $indexedProductConcreteTransfers = [];

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $indexedProductConcreteTransfers[$productConcreteTransfer->getSku()] = $productConcreteTransfer;
        }

        return $indexedProductConcreteTransfers;
    }

    /**
     * @param string[] $skus
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer[]
     */
    protected function getIndexedProductAbstractsByProductAbstractSkus(array $skus): array
    {
        if (!$skus) {
            return [];
        }

        $productAbstractTransfers = $this->productFacade->getRawProductAbstractTransfersByAbstractSkus($skus);
        $indexedProductAbstractTransfers = [];

        foreach ($productAbstractTransfers as $productAbstractTransfer) {
            $indexedProductAbstractTransfers[$productAbstractTransfer->getSku()] = $productAbstractTransfer;
        }

        return $indexedProductAbstractTransfers;
    }
}
