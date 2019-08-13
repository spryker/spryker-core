<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Business\CartChangePreCheck;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedRepositoryInterface;

class CartChangePreCheck implements CartChangePreCheckInterface
{
    protected const GLOSSARY_KEY_CART_PRE_CHECK_PRODUCT_DISCONTINUED = 'cart.pre.check.product_discontinued';
    protected const GLOSSARY_PARAM_NAME = '%name%';
    protected const GLOSSARY_PARAM_SKU = '%sku%';

    /**
     * @var \Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedRepositoryInterface
     */
    protected $productDiscontinuedRepository;

    /**
     * @param \Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedRepositoryInterface $productDiscontinuedRepository
     */
    public function __construct(ProductDiscontinuedRepositoryInterface $productDiscontinuedRepository)
    {
        $this->productDiscontinuedRepository = $productDiscontinuedRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkCartItems(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $cartPreCheckResponseTransfer = $this->createCartPreCheckResponseTransfer();
        $cartPreCheckResponseTransfer = $this->addDiscontinuedErrorMessagesToCartPreCheckResponseTransfer(
            $cartPreCheckResponseTransfer,
            $cartChangeTransfer
        );

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    protected function createCartPreCheckResponseTransfer(): CartPreCheckResponseTransfer
    {
        return (new CartPreCheckResponseTransfer())->setIsSuccess(true);
    }

    /**
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $cartPreCheckResponseTransfer
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    protected function addDiscontinuedErrorMessagesToCartPreCheckResponseTransfer(
        CartPreCheckResponseTransfer $cartPreCheckResponseTransfer,
        CartChangeTransfer $cartChangeTransfer
    ): CartPreCheckResponseTransfer {
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if ($this->isProductDiscontinued($itemTransfer)) {
                $cartPreCheckResponseTransfer->addMessage(
                    $this->createItemIsDiscontinuedMessageTransfer($itemTransfer)
                );
            }
        }

        $cartPreCheckResponseTransfer->setIsSuccess(
            !$cartPreCheckResponseTransfer->getMessages()->count()
        );

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isProductDiscontinued(ItemTransfer $itemTransfer): bool
    {
        return $this->productDiscontinuedRepository->checkIfProductDiscontinuedBySku($itemTransfer->getSku());
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createItemIsDiscontinuedMessageTransfer(ItemTransfer $itemTransfer): MessageTransfer
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue(static::GLOSSARY_KEY_CART_PRE_CHECK_PRODUCT_DISCONTINUED);
        $messageTransfer->setParameters([
            static::GLOSSARY_PARAM_SKU => $itemTransfer->getSku(),
            static::GLOSSARY_PARAM_NAME => $itemTransfer->getName(),
        ]);

        return $messageTransfer;
    }
}
