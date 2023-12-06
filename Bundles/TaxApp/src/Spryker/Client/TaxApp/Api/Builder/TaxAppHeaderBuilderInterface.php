<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\TaxApp\Api\Builder;

use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TaxAppConfigTransfer;
use Generated\Shared\Transfer\TaxCalculationRequestTransfer;

interface TaxAppHeaderBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\TaxCalculationRequestTransfer $taxCalculationRequestTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\TaxAppConfigTransfer $taxAppConfigTransfer
     *
     * @return array<string, string>
     */
    public function build(
        TaxCalculationRequestTransfer $taxCalculationRequestTransfer,
        StoreTransfer $storeTransfer,
        TaxAppConfigTransfer $taxAppConfigTransfer
    ): array;
}
