<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Reader;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer;
use Generated\Shared\Transfer\ProductListResponseTransfer;
use Generated\Shared\Transfer\ProductListTransfer;

interface ConfigurableBundleTemplateSlotReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer[]
     */
    public function getConfigurableBundleTemplateSlotCollection(ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function checkProductListUsageAmongSlots(ProductListTransfer $productListTransfer): ProductListResponseTransfer;
}
