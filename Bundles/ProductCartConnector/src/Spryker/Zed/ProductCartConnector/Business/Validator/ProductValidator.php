<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCartConnector\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductCriteriaTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToProductInterface;

class ProductValidator implements ProductValidatorInterface
{
    /**
     * @var string
     */
    public const MESSAGE_ERROR_ABSTRACT_PRODUCT_EXISTS = 'product-cart.validation.error.abstract-product-exists';

    /**
     * @var string
     */
    public const MESSAGE_ERROR_CONCRETE_PRODUCT_EXISTS = 'product-cart.validation.error.concrete-product-exists';

    /**
     * @var string
     */
    public const MESSAGE_PARAM_SKU = 'sku';

    /**
     * @var string
     */
    public const MESSAGE_ERROR_CONCRETE_PRODUCT_INACTIVE = 'product-cart.validation.error.concrete-product-inactive';

    /**
     * @var string
     */
    protected const SKU_CONCRETE = 'SKU_CONCRETE';

    /**
     * @var string
     */
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
     * @param list<string> $skusToSkip
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateItems(CartChangeTransfer $cartChangeTransfer, array $skusToSkip = [])
    {
        $responseTransfer = new CartPreCheckResponseTransfer();

        $itemTransfers = $cartChangeTransfer->getItems();
        $skus = $this->extractProductSkusFromItemTransfers($itemTransfers);
        $indexedProductConcreteTransfers = $this->getIndexedProductConcretesByProductConcreteSkus($skus[static::SKU_CONCRETE]);
        $indexedProductAbstractTransfers = $this->getIndexedProductAbstractsByProductAbstractSkus($skus[static::SKU_ABSTRACT]);

        foreach ($itemTransfers as $itemTransfer) {
            if (in_array($itemTransfer->getSku(), $skusToSkip)) {
                continue;
            }

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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param list<string> $skusToSkip
     *
     * @return bool
     */
    public function validateCheckoutQuoteItems(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer,
        array $skusToSkip = []
    ): bool {
        if ($quoteTransfer->getItems()->count() === 0) {
            $checkoutResponseTransfer->setIsSuccess(true);

            return true;
        }

        $itemTransfers = $quoteTransfer->getItems();
        $skus = $this->extractProductSkusFromItemTransfers($itemTransfers);
        $indexedProductConcreteTransfers = $this->getIndexedProductConcretesByProductConcreteSkus($skus[static::SKU_CONCRETE]);
        $indexedProductAbstractTransfers = $this->getIndexedProductAbstractsByProductAbstractSkus($skus[static::SKU_ABSTRACT]);

        foreach ($itemTransfers as $itemTransfer) {
            if (in_array($itemTransfer->getSku(), $skusToSkip)) {
                continue;
            }

            if ($itemTransfer->getSku()) {
                $checkoutResponseTransfer = $this->validateProductConcreteTransfersForCheckout(
                    $checkoutResponseTransfer,
                    $indexedProductConcreteTransfers,
                    $itemTransfer->getSkuOrFail(),
                );

                continue;
            }

            $checkoutResponseTransfer = $this->validateProductAbstractTransfersForCheckout(
                $checkoutResponseTransfer,
                $indexedProductAbstractTransfers,
                $itemTransfer->getAbstractSkuOrFail(),
            );
        }

        $isSuccessful = $checkoutResponseTransfer->getErrors()->count() === 0;
        $checkoutResponseTransfer->setIsSuccess($isSuccessful);

        return $isSuccessful;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $responseTransfer
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $indexedProductConcreteTransfers
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
            (new ProductConcreteTransfer())->setSku($concreteSku),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $responseTransfer
     * @param array<\Generated\Shared\Transfer\ProductAbstractTransfer> $indexedProductAbstractTransfers
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
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param array<string, \Generated\Shared\Transfer\ProductConcreteTransfer> $indexedProductConcreteTransfers
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function validateProductConcreteTransfersForCheckout(
        CheckoutResponseTransfer $checkoutResponseTransfer,
        array $indexedProductConcreteTransfers,
        string $sku
    ): CheckoutResponseTransfer {
        if (!isset($indexedProductConcreteTransfers[$sku])) {
            $checkoutErrorTransfer = $this->createCheckoutErrorTransfer(static::MESSAGE_ERROR_CONCRETE_PRODUCT_EXISTS, [
                static::MESSAGE_PARAM_SKU => $sku,
            ]);

            return $checkoutResponseTransfer->addError($checkoutErrorTransfer);
        }

        if ($indexedProductConcreteTransfers[$sku]->getIsActiveOrFail()) {
            return $checkoutResponseTransfer;
        }

        $checkoutErrorTransfer = $this->createCheckoutErrorTransfer(static::MESSAGE_ERROR_CONCRETE_PRODUCT_INACTIVE, [
            static::MESSAGE_PARAM_SKU => $sku,
        ]);

        return $checkoutResponseTransfer->addError($checkoutErrorTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param array<\Generated\Shared\Transfer\ProductAbstractTransfer> $indexedProductAbstractTransfers
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function validateProductAbstractTransfersForCheckout(
        CheckoutResponseTransfer $checkoutResponseTransfer,
        array $indexedProductAbstractTransfers,
        string $sku
    ): CheckoutResponseTransfer {
        if (isset($indexedProductAbstractTransfers[$sku])) {
            return $checkoutResponseTransfer;
        }

        $checkoutErrorTransfer = $this->createCheckoutErrorTransfer(static::MESSAGE_ERROR_ABSTRACT_PRODUCT_EXISTS, [
            static::MESSAGE_PARAM_SKU => $sku,
        ]);

        return $checkoutResponseTransfer->addError($checkoutErrorTransfer);
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
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<string, array<string>>
     */
    protected function extractProductSkusFromItemTransfers(ArrayObject $itemTransfers): array
    {
        $skus = [
            static::SKU_CONCRETE => [],
            static::SKU_ABSTRACT => [],
        ];
        foreach ($itemTransfers as $itemTransfer) {
            if ($itemTransfer->getSku()) {
                $skus[static::SKU_CONCRETE][] = $itemTransfer->getSku();

                continue;
            }

            $skus[static::SKU_ABSTRACT][] = $itemTransfer->getAbstractSku();
        }

        return $skus;
    }

    /**
     * @param array<string> $skus
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    protected function getIndexedProductConcretesByProductConcreteSkus(array $skus): array
    {
        if (!$skus) {
            return [];
        }

        $productCriteriaTransfer = (new ProductCriteriaTransfer())
            ->setSkus($skus)
            ->setWithoutAdditionalProductData(true);

        $productConcreteTransfers = $this->productFacade->getProductConcretesByCriteria($productCriteriaTransfer);
        $indexedProductConcreteTransfers = [];

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $indexedProductConcreteTransfers[$productConcreteTransfer->getSku()] = $productConcreteTransfer;
        }

        return $indexedProductConcreteTransfers;
    }

    /**
     * @param array<string> $skus
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractTransfer>
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

    /**
     * @param string $message
     * @param array<string, mixed> $parameters
     *
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer
     */
    protected function createCheckoutErrorTransfer(string $message, array $parameters = []): CheckoutErrorTransfer
    {
        return (new CheckoutErrorTransfer())
            ->setMessage($message)
            ->setParameters($parameters);
    }
}
