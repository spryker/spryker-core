<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle;

interface ProductBundleGrouperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductForBundleTransfer[] $productForBundleTransfers
     *
     * @return \Generated\Shared\Transfer\ProductBundleTransfer[]
     */
    public function groupProductForBundleTransfersByProductBundleTransfers(array $productForBundleTransfers): array;
}
