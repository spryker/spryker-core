<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBundleStorage\Mapper;

use Generated\Shared\Transfer\ProductForProductBundleStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;

class ProductBundleStorageMapper implements ProductBundleStorageMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productConcreteViewTransfer
     * @param \Generated\Shared\Transfer\ProductForProductBundleStorageTransfer $productForProductBundleStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductForProductBundleStorageTransfer
     */
    public function mapProductViewTransferToProductForProductBundleStorageTransfer(
        ProductViewTransfer $productConcreteViewTransfer,
        ProductForProductBundleStorageTransfer $productForProductBundleStorageTransfer
    ): ProductForProductBundleStorageTransfer {
        return $productForProductBundleStorageTransfer->setName($productConcreteViewTransfer->getName())
            ->setProductImages($productConcreteViewTransfer->getImages())
            ->setUrl($productConcreteViewTransfer->getUrl());
    }
}
