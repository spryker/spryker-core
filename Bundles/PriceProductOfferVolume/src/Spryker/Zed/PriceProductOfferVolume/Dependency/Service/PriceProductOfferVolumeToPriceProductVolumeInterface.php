<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferVolume\Dependency\Service;

use Generated\Shared\Transfer\PriceProductTransfer;

interface PriceProductOfferVolumeToPriceProductVolumeInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return bool
     */
    public function hasVolumePrices(PriceProductTransfer $priceProductTransfer): bool;
}
