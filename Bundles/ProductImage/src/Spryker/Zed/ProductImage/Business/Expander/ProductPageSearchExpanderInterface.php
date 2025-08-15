<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Business\Expander;

use Generated\Shared\Transfer\ProductPageSearchTransfer;

interface ProductPageSearchExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productPageSearchTransfer
     *
     * @return void
     */
    public function expandProductPageSearchTransferWithProductImageAlternativeTexts(
        ProductPageSearchTransfer $productPageSearchTransfer
    ): void;
}
