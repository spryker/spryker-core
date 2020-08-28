<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductImageStorage\Expander;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Shared\ProductImageStorage\ProductImageStorageConfig;

interface ProductViewImageExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $locale
     * @param string $imageSetName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewImageData(
        ProductViewTransfer $productViewTransfer,
        $locale,
        $imageSetName = ProductImageStorageConfig::DEFAULT_IMAGE_SET_NAME
    );
}
