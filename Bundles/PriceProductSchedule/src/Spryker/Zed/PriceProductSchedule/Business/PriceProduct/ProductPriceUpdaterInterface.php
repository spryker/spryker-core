<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProduct;

use Generated\Shared\Transfer\PriceProductTransfer;

interface ProductPriceUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return void
     */
    public function updateCurrentProductPrice(PriceProductTransfer $priceProductTransfer): void;
}
