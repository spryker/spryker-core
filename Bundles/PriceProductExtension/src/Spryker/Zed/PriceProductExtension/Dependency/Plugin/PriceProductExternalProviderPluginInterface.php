<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductExtension\Dependency\Plugin;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;

interface PriceProductExternalProviderPluginInterface
{
    /**
     * Specification:
     * - Returns list of product price transfers by criteria.
     *
     * @api
     *
     * @param string[] $skus
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function providePriceProductTransfers(array $skus, PriceProductCriteriaTransfer $priceProductCriteriaTransfer): array;
}
