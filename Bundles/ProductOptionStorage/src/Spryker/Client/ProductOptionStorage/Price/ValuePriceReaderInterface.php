<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOptionStorage\Price;

use Generated\Shared\Transfer\ProductOptionGroupStorageTransfer;

interface ValuePriceReaderInterface
{

    /**
     * @param ProductOptionGroupStorageTransfer $productOptionGroupStorageTransfer
     *
     * @return ProductOptionGroupStorageTransfer
     */
    public function resolvePrices(ProductOptionGroupStorageTransfer $productOptionGroupStorageTransfer);
}
