<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\TableDataProvider;

use Generated\Shared\Transfer\ProductListTransfer;
use Generated\Shared\Transfer\ProductListUsedByTableTransfer;

interface ProductListUsedByTableProviderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListUsedByTableTransfer
     */
    public function getTableData(ProductListTransfer $productListTransfer): ProductListUsedByTableTransfer;
}
