<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductImageStorage\Expander;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Shared\ProductImageStorage\ProductImageStorageConfig;

interface ProductViewImageExpanderInterface
{
    /**
     * @param ProductViewTransfer $productViewTransfer
     * @param string $locale
     * @param string $imageSetName
     *
     * @return ProductViewTransfer
     */
    public function expandProductViewImageData(ProductViewTransfer $productViewTransfer, $locale, $imageSetName = ProductImageStorageConfig::DEFAULT_IMAGE_SET_NAME);
}
