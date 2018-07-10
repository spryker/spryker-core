<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\VolumePriceProduct\Business\VolumePriceExtractor;

use Generated\Shared\Transfer\PriceProductTransfer;

interface VolumePriceExtractorInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function extractVolumePriceProducts(PriceProductTransfer $priceProductTransfer): array;
}
