<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationCart\Business\Expander;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\ProductConfigurationCart\Dependency\Service\ProductConfigurationCartToProductConfigurationServiceInterface;

class ProductConfigurationGroupKeyItemExpander implements ProductConfigurationGroupKeyItemExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductConfigurationCart\Dependency\Service\ProductConfigurationCartToProductConfigurationServiceInterface
     */
    protected $productConfigurationService;

    /**
     * @param \Spryker\Zed\ProductConfigurationCart\Dependency\Service\ProductConfigurationCartToProductConfigurationServiceInterface $productConfigurationService
     */
    public function __construct(ProductConfigurationCartToProductConfigurationServiceInterface $productConfigurationService)
    {
        $this->productConfigurationService = $productConfigurationService;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandProductConfigurationItemsWithGroupKey(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$this->isProductConfigurationItem($itemTransfer)) {
                continue;
            }

            $itemTransfer->setGroupKey(
                $this->buildProductConfigurationGroupKey($itemTransfer)
            );
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isProductConfigurationItem(ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getProductConfigurationInstance() !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    protected function buildProductConfigurationGroupKey(ItemTransfer $itemTransfer): string
    {
        $productConfigurationInstanceHashKey = $this->productConfigurationService->getProductConfigurationInstanceHash(
            $itemTransfer->getProductConfigurationInstanceOrFail()
        );

        return sprintf(
            '%s-%s',
            $itemTransfer->getGroupKeyOrFail(),
            $productConfigurationInstanceHashKey
        );
    }
}
