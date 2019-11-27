<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Combiner;

interface ProductImageSetCombinerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer[] $localizedProductImageSetTransfers
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer[] $defaultProductImageSetTransfers
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function combineProductImageSets(array $localizedProductImageSetTransfers, array $defaultProductImageSetTransfers): array;
}
