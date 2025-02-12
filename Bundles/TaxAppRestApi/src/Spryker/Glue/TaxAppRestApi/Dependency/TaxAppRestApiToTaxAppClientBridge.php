<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\TaxAppRestApi\Dependency;

use Generated\Shared\Transfer\TaxAppValidationRequestTransfer;
use Generated\Shared\Transfer\TaxAppValidationResponseTransfer;

class TaxAppRestApiToTaxAppClientBridge implements TaxAppRestApiToTaxAppClientInterface
{
    /**
     * @var \Spryker\Client\TaxApp\TaxAppClientInterface
     */
    protected $taxAppClient;

    /**
     * @param \Spryker\Client\TaxApp\TaxAppClientInterface $taxAppClient
     */
    public function __construct($taxAppClient)
    {
        $this->taxAppClient = $taxAppClient;
    }

    /**
     * @param \Generated\Shared\Transfer\TaxAppValidationRequestTransfer $taxAppValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\TaxAppValidationResponseTransfer
     */
    public function validateTaxId(TaxAppValidationRequestTransfer $taxAppValidationRequestTransfer): TaxAppValidationResponseTransfer
    {
        return $this->taxAppClient->validateTaxId($taxAppValidationRequestTransfer);
    }
}
