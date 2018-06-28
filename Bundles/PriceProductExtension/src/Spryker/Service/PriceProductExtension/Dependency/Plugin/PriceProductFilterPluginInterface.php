<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductExtension\Dependency\Plugin;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;

interface PriceProductFilterPluginInterface
{
    /**
     * Specification:
     * - Filters passed prices by the additional business logic
     *
     * @param PriceProductTransfer[] $priceProductTransfers
     * @param PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return PriceProductTransfer[]
     */
    public function filter(array $priceProductTransfers, PriceProductFilterTransfer $priceProductFilterTransfer): array;

    /**
     * Specification:
     *  - Returns dimension name.
     *
     * @api
     *
     * @return string
     */
    public function getDimensionName(): string;
}
