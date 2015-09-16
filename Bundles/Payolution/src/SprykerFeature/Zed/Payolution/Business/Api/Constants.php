<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api;

use SprykerFeature\Shared\Library\ConfigInterface;

interface Constants extends ConfigInterface
{

    const PAYMENT_CODE_PRE_CHECK = 'VA.PA';
    const PAYMENT_CODE_PRE_AUTHORIZATION = 'VA.PA';
    const PAYMENT_CODE_RE_AUTHORIZACTION = 'VA.PA';
    const PAYMENT_CODE_CAPTURE = 'VA.CP';
    const PAYMENT_CODE_REVERSAL = 'VA.RV';
    const PAYMENT_CODE_REFUND = 'VA.RF';

    const TRANSACTION_MODE_TEST = 'CONNECTOR_TEST';
    const TRANSACTION_MODE_LIVE = 'LIVE';

    const SEX_MALE = 'M';
    const SEX_FEMALE = 'F';

    const ACCOUNT_BRAND_INVOICE = 'PAYOLUTION_INVOICE';
    const ACCOUNT_BRAND_INSTALLMENT = 'PAYOLUTION_INS';

    const STATUS_CODE_SUCCESS = '90';
    const REASON_CODE_SUCCESS = '00';
    const STATUS_REASON_CODE_SUCCESS = self::STATUS_CODE_SUCCESS . '.' . self::REASON_CODE_SUCCESS;

}
