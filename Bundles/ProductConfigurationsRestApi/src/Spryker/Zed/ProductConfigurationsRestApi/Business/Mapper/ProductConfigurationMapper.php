<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationsRestApi\Business\Mapper;

use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;

class ProductConfigurationMapper implements ProductConfigurationMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function mapCartItemRequestTransferToPersistentCartChangeTransfer(
        CartItemRequestTransfer $cartItemRequestTransfer,
        PersistentCartChangeTransfer $persistentCartChangeTransfer
    ): PersistentCartChangeTransfer {
        $productConfigurationInstanceTransfer = $cartItemRequestTransfer->getProductConfigurationInstance();
        if (!$productConfigurationInstanceTransfer) {
            return $persistentCartChangeTransfer;
        }

        foreach ($persistentCartChangeTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getSkuOrFail() !== $cartItemRequestTransfer->getSkuOrFail()) {
                continue;
            }

            $itemTransfer->setProductConfigurationInstance($productConfigurationInstanceTransfer);
        }

        return $persistentCartChangeTransfer;
    }
}
