<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductExtension\Dependency\Plugin;

use Generated\Shared\Transfer\PriceProductTransfer;

/**
 * Implement this plugin if you need to modify PriceProduct transfer before building the group key.
 */
interface PreBuildPriceProductGroupKeyPluginInterface
{
    /**
     * Specification:
     *  - Prepare PriceProduct transfer before building the price product group key.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function preBuild(PriceProductTransfer $priceProductTransfer): PriceProductTransfer;
}
