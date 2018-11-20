<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\PreCheck;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface;

class ProductBundleCartActiveCheck implements ProductBundleCartActiveCheckInterface
{
    protected const CART_PRE_CHECK_ACTIVE_FAILED = 'cart.pre.check.active.failed';
    protected const TRANSLATION_PARAMETER_SKU = '%sku%';

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
    public function checkActiveItems(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $cartPreCheckResponseTransfer = new CartPreCheckResponseTransfer();
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $errorMessageTransfer = $this->checkBundledProductsActive($itemTransfer);
            if ($errorMessageTransfer !== null) {
                $cartPreCheckResponseTransfer->addMessage($errorMessageTransfer);
            }
        }

        $cartPreCheckResponseTransfer
            ->setIsSuccess($cartPreCheckResponseTransfer->getMessages()->count() === 0);

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\MessageTransfer|null
     */
    protected function checkBundledProductsActive(ItemTransfer $itemTransfer): ?MessageTransfer
    {
        $productEntityTransfers = $this->productBundleRepository->findBundledProductsBySku(
            $itemTransfer->getSku()
        );

        foreach ($productEntityTransfers as $productEntityTransfer) {
            if (!$productEntityTransfer->getIsActive()) {
                return $this->createItemIsNotActiveMessageTransfer($itemTransfer->getSku());
            }
        }

        return null;
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createItemIsNotActiveMessageTransfer(string $sku): MessageTransfer
    {
        return (new MessageTransfer())
            ->setValue(static::CART_PRE_CHECK_ACTIVE_FAILED)
            ->setParameters([
                static::TRANSLATION_PARAMETER_SKU => $sku,
            ]);
    }
}
