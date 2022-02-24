<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\PriceProduct;

use Generated\Shared\Transfer\PriceProductTransfer;

interface PriceProductRemoverInterface
{
    /**
     * @deprecated Please try to avoid removing price product store.
     *   Use {@link \Spryker\Zed\PriceProduct\Business\PriceProduct\PriceProductDefaultRemover::removePriceProductDefaultsForPriceProduct} instead.
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return void
     */
    public function removePriceProductStore(PriceProductTransfer $priceProductTransfer): void;
}
