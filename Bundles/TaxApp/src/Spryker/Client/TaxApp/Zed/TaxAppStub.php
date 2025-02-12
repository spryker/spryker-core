<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\TaxApp\Zed;

use Generated\Shared\Transfer\TaxAppValidationRequestTransfer;
use Generated\Shared\Transfer\TaxAppValidationResponseTransfer;
use Spryker\Client\TaxApp\Dependency\Client\TaxAppToZedRequestClientInterface;

class TaxAppStub implements TaxAppStubInterface
{
    /**
     * @param \Spryker\Client\TaxApp\Dependency\Client\TaxAppToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(protected TaxAppToZedRequestClientInterface $zedRequestClient)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\TaxAppValidationRequestTransfer $taxAppValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\TaxAppValidationResponseTransfer
     */
    public function validateTaxId(TaxAppValidationRequestTransfer $taxAppValidationRequestTransfer): TaxAppValidationResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\TaxAppValidationResponseTransfer $taxAppValidationResponseTransfer */
        $taxAppValidationResponseTransfer = $this->zedRequestClient->call('/tax-app/gateway/validate-tax-id', $taxAppValidationRequestTransfer);

        return $taxAppValidationResponseTransfer;
    }
}
