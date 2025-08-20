<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetPageSearch\Business\Expander;

use Generated\Shared\Transfer\ProductSetPageSearchTransfer;

interface ProductSetPageSearchExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductSetPageSearchTransfer $productSetPageSearchTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSetPageSearchTransfer
     */
    public function expandProductSetPageSearchWithProductImageAlternativeTexts(
        ProductSetPageSearchTransfer $productSetPageSearchTransfer
    ): ProductSetPageSearchTransfer;
}
