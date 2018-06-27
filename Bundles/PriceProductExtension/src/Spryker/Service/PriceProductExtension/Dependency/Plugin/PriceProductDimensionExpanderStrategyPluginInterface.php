<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductExtension\Dependency\Plugin;

use Generated\Shared\Transfer\PriceProductDimensionTransfer;

/**
 * Just first applicable plugin will be executed.
 */
interface PriceProductDimensionExpanderStrategyPluginInterface
{
    /**
     * Specification:
     *  - Returns true if strategy can be used for the transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductDimensionTransfer $priceProductDimensionTransfer
     *
     * @return bool
     */
    public function isApplicable(PriceProductDimensionTransfer $priceProductDimensionTransfer): bool;

    /**
     * Specification:
     *  - Returns expanded transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductDimensionTransfer $priceProductDimensionTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductDimensionTransfer
     */
    public function expand(PriceProductDimensionTransfer $priceProductDimensionTransfer): PriceProductDimensionTransfer;
}
