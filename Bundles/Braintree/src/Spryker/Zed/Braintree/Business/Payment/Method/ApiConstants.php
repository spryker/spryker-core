<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Business\Payment\Method;

interface ApiConstants
{

    const ACCOUNT_BRAND = 'ACCOUNT.BRAND';
    const METHOD_PAY_PAL = 'BRAINTREE_PAY_PAL';

    const TRANSACTION_MODE = 'TRANSACTION.MODE';
    const TRANSACTION_MODE_TEST = 'CONNECTOR_TEST';
    const TRANSACTION_MODE_LIVE = 'LIVE';
    const TRANSACTION_CHANNEL = 'TRANSACTION.CHANNEL';

    const PRESENTATION_AMOUNT = 'PRESENTATION.AMOUNT';
    const PRESENTATION_USAGE = 'PRESENTATION.USAGE';
    const PRESENTATION_CURRENCY = 'PRESENTATION.CURRENCY';

    const IDENTIFICATION_TRANSACTIONID = 'IDENTIFICATION.TRANSACTIONID';
    const IDENTIFICATION_SHOPPERID = 'IDENTIFICATION.SHOPPERID';
    const IDENTIFICATION_REFERENCEID = 'IDENTIFICATION.REFERENCEID';

    const NAME_GIVEN = 'NAME.GIVEN';
    const NAME_FAMILY = 'NAME.FAMILY';
    const NAME_TITLE = 'NAME.TITLE';
    const NAME_SEX = 'NAME.SEX';
    const NAME_BIRTHDATE = 'NAME.BIRTHDATE';

    const ADDRESS_STREET = 'ADDRESS.STREET';
    const ADDRESS_ZIP = 'ADDRESS.ZIP';
    const ADDRESS_CITY = 'ADDRESS.CITY';
    const ADDRESS_COUNTRY = 'ADDRESS.COUNTRY';

    const PAYMENT_CODE_PRE_CHECK = 'authorized';
    const PAYMENT_CODE_AUTHORIZE = 'authorized';
    const PAYMENT_CODE_CAPTURE = 'submitted_for_settlement'; // Braintree\Transaction::SETTLEMENT_CONFIRMED
    const PAYMENT_CODE_REVERSAL = 'voided';
    const PAYMENT_CODE_REFUND = 'submitted_for_settlement';

    const PAYMENT_CODE_AUTHORIZE_SUCCESS = '1000';
    const STATUS_REASON_CODE_SUCCESS = '1';


}
