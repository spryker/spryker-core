<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelSearch\Business\PageData;

use Generated\Shared\Transfer\ProductPageLoadTransfer;

interface ProductPageDataTransferExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $productPageLoadTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPageLoadTransfer
     */
    public function expandProductPageDataTransferWithProductLabelIds(ProductPageLoadTransfer $productPageLoadTransfer);
}
