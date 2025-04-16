<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductExtension\Dependency\Plugin;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;

/**
 * Provides extension capabilities for executing business logic code after resolving prices.
 */
interface PriceProductPostResolvePluginInterface
{
    /**
     * Specification:
     * - Executes after resolving prices for the resulting `PriceProductTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function postResolve(
        PriceProductTransfer $priceProductTransfer,
        PriceProductFilterTransfer $priceProductFilterTransfer
    ): PriceProductTransfer;
}
