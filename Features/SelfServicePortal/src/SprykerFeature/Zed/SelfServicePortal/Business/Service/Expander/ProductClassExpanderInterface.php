<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ProductPageLoadTransfer;

interface ProductClassExpanderInterface
{
    public function expandProductPageDataTransferWithProductClasses(
        ProductPageLoadTransfer $productPageLoadTransfer
    ): ProductPageLoadTransfer;

    public function expandItems(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer;
}
