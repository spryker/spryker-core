<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\TaxApp\Api\Builder;

use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TaxAppConfigTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

interface TaxAppHeaderBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\TaxCalculationRequestTransfer|\Generated\Shared\Transfer\TaxRefundRequestTransfer $taxRequestTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\TaxAppConfigTransfer $taxAppConfigTransfer
     *
     * @throws \Spryker\Client\TaxApp\Exception\TaxAppInvalidConfigException
     *
     * @return array<string, string>
     */
    public function build(
        AbstractTransfer $taxRequestTransfer,
        StoreTransfer $storeTransfer,
        TaxAppConfigTransfer $taxAppConfigTransfer
    ): array;
}
