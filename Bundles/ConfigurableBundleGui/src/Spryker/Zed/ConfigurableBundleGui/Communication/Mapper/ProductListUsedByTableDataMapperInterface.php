<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication\Mapper;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ProductListUsedByTableRowTransfer;

interface ProductListUsedByTableDataMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     * @param \Generated\Shared\Transfer\ProductListUsedByTableRowTransfer $productListUsedByTableRowTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListUsedByTableRowTransfer
     */
    public function mapConfigurableBundleTemplateSlotTransferToProductListUsedByTableRowTransfer(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer,
        ProductListUsedByTableRowTransfer $productListUsedByTableRowTransfer
    ): ProductListUsedByTableRowTransfer;
}
