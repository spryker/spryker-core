<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOptionsRestApi\Processor\Expander;

use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\RestCartItemsAttributesTransfer;
use Spryker\Glue\ProductOptionsRestApi\Processor\Reader\ProductOptionStorageReaderInterface;

class CartItemExpander implements CartItemExpanderInterface
{
    /**
     * @var \Spryker\Glue\ProductOptionsRestApi\Processor\Reader\ProductOptionStorageReaderInterface
     */
    protected $productOptionStorageReader;

    /**
     * @param \Spryker\Glue\ProductOptionsRestApi\Processor\Reader\ProductOptionStorageReaderInterface $productOptionStorageReader
     */
    public function __construct(ProductOptionStorageReaderInterface $productOptionStorageReader)
    {
        $this->productOptionStorageReader = $productOptionStorageReader;
    }

    /**
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\CartItemRequestTransfer
     */
    public function expand(
        CartItemRequestTransfer $cartItemRequestTransfer,
        RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
    ): CartItemRequestTransfer {
        $productOptionIds = $this->productOptionStorageReader->getProductOptionIdsByProductConcreteSku(
            $this->resolveProductConcreteSku($cartItemRequestTransfer, $restCartItemsAttributesTransfer)
        );
        foreach ($restCartItemsAttributesTransfer->getProductOptions() as $restCartItemsProductOptionTransfer) {
            if (!isset($productOptionIds[$restCartItemsProductOptionTransfer->getSku()])) {
                continue;
            }

            $cartItemRequestTransfer->addProductOption(
                (new ProductOptionTransfer())
                    ->setIdProductOptionValue($productOptionIds[$restCartItemsProductOptionTransfer->getSku()])
            );
        }

        return $cartItemRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return string|null
     */
    protected function resolveProductConcreteSku(
        CartItemRequestTransfer $cartItemRequestTransfer,
        RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
    ): ?string {
        return $restCartItemsAttributesTransfer->getSku() ?? $cartItemRequestTransfer->getSku();
    }
}
