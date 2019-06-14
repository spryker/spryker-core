<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\PreCheck;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface;

class ProductBundleCartActiveCheck implements ProductBundleCartActiveCheckInterface
{
    protected const GLOSSARY_KEY_CART_PRE_CHECK_ACTIVE_FAILED = 'cart.pre.check.active.failed';
    protected const GLOSSARY_PARAMETER_SKU = '%sku%';

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
        $cartPreCheckResponseTransfer = (new CartPreCheckResponseTransfer())
            ->setIsSuccess(true);

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$this->checkBundledProductsActive($itemTransfer->getSku())) {
                $cartPreCheckResponseTransfer
                    ->addMessage(
                        $this->createItemIsNotActiveMessageTransfer($itemTransfer->getSku())
                    )
                    ->setIsSuccess(false);
            }
        }

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    protected function checkBundledProductsActive(string $sku): bool
    {
        $productForBundleTransfers = $this->productBundleRepository->findBundledProductsBySku($sku);

        foreach ($productForBundleTransfers as $productForBundleTransfer) {
            if (!$productForBundleTransfer->getIsActive()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createItemIsNotActiveMessageTransfer(string $sku): MessageTransfer
    {
        return (new MessageTransfer())
            ->setValue(static::GLOSSARY_KEY_CART_PRE_CHECK_ACTIVE_FAILED)
            ->setParameters([
                static::GLOSSARY_PARAMETER_SKU => $sku,
            ]);
    }
}
