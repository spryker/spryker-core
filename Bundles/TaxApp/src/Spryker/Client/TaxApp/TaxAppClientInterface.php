<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\TaxApp;

use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TaxAppConfigTransfer;
use Generated\Shared\Transfer\TaxCalculationRequestTransfer;
use Generated\Shared\Transfer\TaxCalculationResponseTransfer;
use Generated\Shared\Transfer\TaxRefundRequestTransfer;

interface TaxAppClientInterface
{
    /**
     * Specification:
     * - Sends a request for Tax Quotation to a foreign Tax Calculation service.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\TaxCalculationRequestTransfer $taxCalculationRequest
     * @param \Generated\Shared\Transfer\TaxAppConfigTransfer $taxAppConfigTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\TaxCalculationResponseTransfer
     */
    public function requestTaxQuotation(
        TaxCalculationRequestTransfer $taxCalculationRequest,
        TaxAppConfigTransfer $taxAppConfigTransfer,
        StoreTransfer $storeTransfer
    ): TaxCalculationResponseTransfer;

    /**
     * Specification:
     * - Sends a request for Tax Refunds to a foreign Tax Calculation service.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\TaxRefundRequestTransfer $taxRefundRequest
     * @param \Generated\Shared\Transfer\TaxAppConfigTransfer $taxAppConfigTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\TaxCalculationResponseTransfer
     */
    public function requestTaxRefund(
        TaxRefundRequestTransfer $taxRefundRequest,
        TaxAppConfigTransfer $taxAppConfigTransfer,
        StoreTransfer $storeTransfer
    ): TaxCalculationResponseTransfer;
}
