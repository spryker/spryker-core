<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Payolution;

use SprykerFeature\Shared\Library\ConfigInterface;

interface PayolutionApiConstants extends ConfigInterface
{

    const BRAND_INVOICE = 'PAYOLUTION_INVOICE';
    const BRAND_INSTALLMENT = 'PAYOLUTION_INS';

    const PAYMENT_CODE_PRE_CHECK = 'VA.PA';

    const STATUS_CODE_SUCCESS = '90';
    const REASON_CODE_SUCCESS = '00';
    const STATUS_REASON_CODE_SUCCESS = self::STATUS_CODE_SUCCESS . '.' . self::REASON_CODE_SUCCESS;

}
