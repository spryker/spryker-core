<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\TaxApp;

use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TaxAppConfigTransfer;
use Generated\Shared\Transfer\TaxAppValidationRequestTransfer;
use Generated\Shared\Transfer\TaxAppValidationResponseTransfer;
use Generated\Shared\Transfer\TaxCalculationRequestTransfer;
use Generated\Shared\Transfer\TaxCalculationResponseTransfer;
use Generated\Shared\Transfer\TaxRefundRequestTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\TaxApp\TaxAppFactory getFactory()
 */
class TaxAppClient extends AbstractClient implements TaxAppClientInterface
{
    /**
     * {@inheritDoc}
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
    ): TaxCalculationResponseTransfer {
        return $this->getFactory()->createTaxAppRequestSender()->requestTaxQuotation($taxCalculationRequest, $taxAppConfigTransfer, $storeTransfer);
    }

    /**
     * {@inheritDoc}
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
    ): TaxCalculationResponseTransfer {
        return $this->getFactory()->createTaxAppRequestSender()->requestTaxRefund($taxRefundRequest, $taxAppConfigTransfer, $storeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\TaxAppValidationRequestTransfer $taxAppValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\TaxAppValidationResponseTransfer
     */
    public function validateTaxId(TaxAppValidationRequestTransfer $taxAppValidationRequestTransfer): TaxAppValidationResponseTransfer
    {
        return $this->getFactory()
            ->createZedStub()
            ->validateTaxId($taxAppValidationRequestTransfer);
    }
}
