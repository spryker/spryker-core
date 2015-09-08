<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Payolution;

use SprykerFeature\Shared\Library\ConfigInterface;

interface PayolutionConfigConstants extends ConfigInterface
{

    const GATEWAY_URL = 'PAYOLUTION_GATEWAY_URL';
    const SECURITY_SENDER = 'PAYOLUTION_SECURITY_SENDER';
    const USER_LOGIN = 'PAYOLUTION_USER_LOGIN';
    const USER_PASSWORD = 'PAYOLUTION_USER_PASSWORD';
    const TRANSACTION_MODE = 'PAYOLUTION_TRANSACTION_MODE';
    const CHANNEL_INVOICE = 'PAYOLUTION_CHANNEL_INVOICE';
    const CHANNEL_INSTALLMENT = 'PAYOLUTION_CHANNEL_INSTALLMENT';
    const CHANNEL_PRE_CHECK_ID = 'PAYOLUTION_CHANNEL_PRE_CHECK_ID';

}
