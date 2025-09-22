<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\TableDataProvider;

use Generated\Shared\Transfer\ProductListUsedByTableTransfer;

interface SspModelProductListUsedByTableExpanderInterface
{
    public function expandTableData(ProductListUsedByTableTransfer $productListUsedByTableTransfer): ProductListUsedByTableTransfer;
}
