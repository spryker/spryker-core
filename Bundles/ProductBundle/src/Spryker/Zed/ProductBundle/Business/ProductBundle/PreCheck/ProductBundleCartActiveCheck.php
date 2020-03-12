<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\PreCheck;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleReaderInterface;

class ProductBundleCartActiveCheck implements ProductBundleCartActiveCheckInterface
{
    protected const GLOSSARY_KEY_CART_PRE_CHECK_ACTIVE_FAILED = 'cart.pre.check.active.failed';
    protected const GLOSSARY_PARAMETER_SKU = '%sku%';

    /**
     * @var \Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleReaderInterface
     */
    protected $productBundleReader;

    /**
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleReaderInterface $productBundleReader
     */
    public function __construct(ProductBundleReaderInterface $productBundleReader)
    {
        $this->productBundleReader = $productBundleReader;
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
        $productConcreteSkus = $this->getProductConcreteSkusFromCartChangeTransfer($cartChangeTransfer);
        $groupedProductForBundleTransfers = $this
            ->productBundleReader
            ->getProductForBundleTransfersByProductConcreteSkus($productConcreteSkus);

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$this->checkBundledProductsActive($groupedProductForBundleTransfers[$itemTransfer->getSku()] ?? [])) {
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
     * @param \Generated\Shared\Transfer\ProductForBundleTransfer[] $productForBundleTransfers
     *
     * @return bool
     */
    protected function checkBundledProductsActive(array $productForBundleTransfers): bool
    {
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

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return string[]
     */
    protected function getProductConcreteSkusFromCartChangeTransfer(CartChangeTransfer $cartChangeTransfer): array
    {
        $skus = [];

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $skus[] = $itemTransfer->getSku();
        }

        return $skus;
    }
}
