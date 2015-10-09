<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Payolution;

use SprykerFeature\Shared\Library\ConfigInterface;

interface PayolutionConfigConstants extends ConfigInterface
{

    const GATEWAY_URL = 'PAYOLUTION_GATEWAY_URL';
    const DEFAULT_TIMEOUT = 45;
    const SECURITY_SENDER = 'PAYOLUTION_SECURITY_SENDER';
    const USER_LOGIN = 'PAYOLUTION_USER_LOGIN';
    const USER_PASSWORD = 'PAYOLUTION_USER_PASSWORD';
    const TRANSACTION_MODE = 'PAYOLUTION_TRANSACTION_MODE';
    const TRANSACTION_CHANNEL_INVOICE = 'PAYOLUTION_CHANNEL_INVOICE';
    const TRANSACTION_CHANNEL_INSTALLMENT = 'PAYOLUTION_CHANNEL_INSTALLMENT';
    const TRANSACTION_CHANNEL_SYNC = 'PAYOLUTION_CHANNEL_SYNC';
    const TRANSACTION_CHANNEL_PRE_CHECK = 'PAYOLUTION_CHANNEL_PRE_CHECK_ID';

}
