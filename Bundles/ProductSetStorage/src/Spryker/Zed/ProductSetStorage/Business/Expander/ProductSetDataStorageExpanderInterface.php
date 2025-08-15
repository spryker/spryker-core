<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetStorage\Business\Expander;

use Generated\Shared\Transfer\ProductSetDataStorageTransfer;

interface ProductSetDataStorageExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductSetDataStorageTransfer $productSetDataStorageTransfer
     * @param array<mixed> $spyProductSetLocalizedEntity
     *
     * @return \Generated\Shared\Transfer\ProductSetDataStorageTransfer
     */
    public function expandProductSetDataStorageWithProductImageAlternativeTexts(
        ProductSetDataStorageTransfer $productSetDataStorageTransfer,
        array $spyProductSetLocalizedEntity
    ): ProductSetDataStorageTransfer;
}
