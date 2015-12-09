<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Payolution;

interface PayolutionApiConstants
{

    const BRAND_INVOICE = 'PAYOLUTION_INVOICE';
    const BRAND_INSTALLMENT = 'PAYOLUTION_INS';

    const PAYMENT_CODE_PRE_CHECK = 'VA.PA';

    const STATUS_CODE_SUCCESS = '90';
    const REASON_CODE_SUCCESS = '00';
    const STATUS_REASON_CODE_SUCCESS = self::STATUS_CODE_SUCCESS . '.' . self::REASON_CODE_SUCCESS;

    const CRITERION_REQUEST_SYSTEM_VENDOR = 'Spryker';
    const CRITERION_REQUEST_SYSTEM_VERSION = '1.0';
    const CRITERION_REQUEST_SYSTEM_TYPE = 'Webshop';
    const WEBSHOP_URL = 'HOST_YVES';

}
