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
use Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToProductInterface;

class ProductValidator implements ProductValidatorInterface
{
    const MESSAGE_ERROR_ABSTRACT_PRODUCT_EXISTS = 'product-cart.validation.error.abstract-product-exists';
    const MESSAGE_ERROR_CONCRETE_PRODUCT_EXISTS = 'product-cart.validation.error.concrete-product-exists';
    const MESSAGE_PARAM_SKU = 'sku';
    public const MESSAGE_ERROR_CONCRETE_PRODUCT_INACTIVE = 'product-cart.validation.error.concrete-product-inactive';

    /** @var \Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToProductInterface */
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

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getSku()) {
                $this->productStatusCheck($itemTransfer, $responseTransfer);
                $this->validateConcreteItem($itemTransfer, $responseTransfer);
                continue;
            }

            $this->validateAbstractItem($itemTransfer, $responseTransfer);
        }

        return $this->setResponseSuccessful($responseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function validateConcreteItem(ItemTransfer $itemTransfer, CartPreCheckResponseTransfer $responseTransfer)
    {
        $isValid = $this->productFacade->hasProductConcrete($itemTransfer->getSku());

        if ($isValid) {
            return;
        }

        $message = $this->createViolationMessage(static::MESSAGE_ERROR_CONCRETE_PRODUCT_EXISTS);
        $message->setParameters([
            static::MESSAGE_PARAM_SKU => $itemTransfer->getSku(),
        ]);

        $responseTransfer->addMessage($message);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function productStatusCheck(ItemTransfer $itemTransfer, CartPreCheckResponseTransfer $responseTransfer): void
    {
        $isValid = $this->productFacade->isProductConcreteActive($itemTransfer->getSku());

        if ($isValid) {
            return;
        }

        $message = $this->createViolationMessage(static::MESSAGE_ERROR_CONCRETE_PRODUCT_INACTIVE);
        $message->setParameters([
            static::MESSAGE_PARAM_SKU => $itemTransfer->getSku(),
        ]);

        $responseTransfer->addMessage($message);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function validateAbstractItem(ItemTransfer $itemTransfer, CartPreCheckResponseTransfer $responseTransfer)
    {
        $isValid = $this->productFacade->hasProductAbstract($itemTransfer->getAbstractSku());

        if ($isValid) {
            return;
        }

        $message = $this->createViolationMessage(static::MESSAGE_ERROR_CONCRETE_PRODUCT_EXISTS);
        $message->setParameters([
            static::MESSAGE_PARAM_SKU => $itemTransfer->getAbstractSku(),
        ]);

        $responseTransfer->addMessage($message);
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
}
