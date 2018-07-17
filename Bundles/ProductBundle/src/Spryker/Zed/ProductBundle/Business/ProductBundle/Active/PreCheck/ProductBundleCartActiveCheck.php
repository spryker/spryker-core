<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Active\PreCheck;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface;

class ProductBundleCartActiveCheck implements ProductBundleCartActiveCheckInterface
{
    public const CART_PRE_CHECK_ACTIVE_FAILED = 'cart.pre.check.active.failed';

    /**
     * @var \Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface
     */
    protected $productBundleRepository;

    /**
     * @param \Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface $productBundleRepository
     */
    public function __construct(ProductBundleRepositoryInterface $productBundleRepository)
    {
        $this->productBundleRepository = $productBundleRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkCartAvailability(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$this->isBundledProductsActive($itemTransfer)) {
                return $this->getFailedResponse();
            }
        }

        return $this->getSuccessResponse();
    }

    /**
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    protected function getFailedResponse(): CartPreCheckResponseTransfer
    {
        return $this->createCartPreCheckResponseTransfer(
            $this->createItemIsNotActiveMessageTransfer()
        );
    }

    /**
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    protected function getSuccessResponse(): CartPreCheckResponseTransfer
    {
        return $this->createCartPreCheckResponseTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isBundledProductsActive(ItemTransfer $itemTransfer): bool
    {
        $productEntityTransfers = $this->findBundledProducts($itemTransfer->getSku());

        foreach ($productEntityTransfers as $productEntityTransfer) {
            if (!$productEntityTransfer->getIsActive()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\SpyProductEntityTransfer[]
     */
    protected function findBundledProducts(string $sku): array
    {
        return $this->productBundleRepository->findBundledProductsBySku($sku);
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer|null $message
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    protected function createCartPreCheckResponseTransfer(?MessageTransfer $message = null): CartPreCheckResponseTransfer
    {
        $cartPreCheckResponseTransfer = new CartPreCheckResponseTransfer();
        $cartPreCheckResponseTransfer->setIsSuccess($message === null);

        if ($message !== null) {
            $cartPreCheckResponseTransfer->addMessage($message);
        }

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createItemIsNotActiveMessageTransfer(): MessageTransfer
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue(static::CART_PRE_CHECK_ACTIVE_FAILED);

        return $messageTransfer;
    }
}
