<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication\Expander;

use Generated\Shared\Transfer\ProductListUsedByTableTransfer;

interface ProductListUsedByTableExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductListUsedByTableTransfer $productListUsedByTableTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListUsedByTableTransfer
     */
    public function expandTableData(ProductListUsedByTableTransfer $productListUsedByTableTransfer): ProductListUsedByTableTransfer;
}
