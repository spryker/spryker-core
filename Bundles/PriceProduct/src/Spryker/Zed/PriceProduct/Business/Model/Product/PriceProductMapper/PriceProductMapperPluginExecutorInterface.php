<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductMapper;

use Generated\Shared\Transfer\PriceProductTransfer;

interface PriceProductMapperPluginExecutorInterface
{
    /**
     * @param PriceProductTransfer $priceProductTransfer
     *
     * @return PriceProductTransfer[]
     */
    public function executePriceExtractorPlugins(PriceProductTransfer $priceProductTransfer): array;
}