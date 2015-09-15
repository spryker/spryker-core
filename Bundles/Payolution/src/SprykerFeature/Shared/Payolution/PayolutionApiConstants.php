<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Payolution;

use SprykerFeature\Shared\Library\ConfigInterface;
use SprykerFeature\Zed\Payolution\Business\Api\Constants;

interface PayolutionApiConstants extends ConfigInterface
{

    const BRAND_INVOICE = Constants::ACCOUNT_BRAND_INVOICE;
    const BRAND_INSTALLMENT = Constants::ACCOUNT_BRAND_INSTALLMENT;

}
