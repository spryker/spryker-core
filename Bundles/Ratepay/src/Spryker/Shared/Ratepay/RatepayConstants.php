<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Ratepay;

interface RatepayConstants
{
    const PROVIDER_NAME = 'Ratepay';

    const PAYMENT_METHOD_INVOICE = 'ratepayInvoice';
    const PAYMENT_METHOD_ELV = 'ratepayElv';
    const PAYMENT_METHOD_INSTALLMENT = 'ratepayInstallment';
    const PAYMENT_METHOD_PREPAYMENT = 'ratepayPrepayment';

    const INVOICE = 'INVOICE';
    const ELV = 'ELV';
    const INSTALLMENT = 'INSTALLMENT';
    const PREPAYMENT = 'PREPAYMENT';

    const PROFILE_ID = 'RATEPAY_PROFILE_ID';
    const SECURITY_CODE = 'RATEPAY_SECURITY_CODE';
    const SNIPPET_ID = 'RATEPAY_SNIPPET_ID';
    const SHOP_ID = 'RATEPAY_SHOP_ID';
    const SYSTEM_ID = 'RATEPAY_SYSTEM_ID';

    /**
     * API modes urls.
     */
    const API_URL = 'RATEPAY_API_URL';

    /**
     * Payment submethods.
     */
    const METHOD_INVOICE = 'INVOICE';
    const METHOD_ELV = 'ELV';
    const METHOD_PREPAYMENT = 'PREPAYMENT';
    const METHOD_INSTALLMENT = 'INSTALLMENT';

    const PAYMENT_METHODS_MAP = [
        self::METHOD_INVOICE => 'ratepayInvoice',
        self::METHOD_ELV => 'ratepayElv',
        self::METHOD_INSTALLMENT => 'ratepayInstallment',
        self::METHOD_PREPAYMENT => 'ratepayPrepayment',
    ];

    /**
     * Service method
     */
    const METHOD_SERVICE = 'SERVICE';

    /**
     * Installment debit pay type.
     */
    const DEBIT_PAY_TYPE_DIRECT_DEBIT = 'DIRECT-DEBIT';
    const DEBIT_PAY_TYPE_BANK_TRANSFER = 'BANK-TRANSFER';
    const DEBIT_PAY_TYPES = [
        self::DEBIT_PAY_TYPE_DIRECT_DEBIT,
        self::DEBIT_PAY_TYPE_BANK_TRANSFER,
    ];

    /**
     * Installment calculator types.
     */
    const INSTALLMENT_CALCULATION_TIME = 'calculation-by-time';
    const INSTALLMENT_CALCULATION_RATE = 'calculation-by-rate';
    const INSTALLMENT_CALCULATION_TYPES = [
        self::INSTALLMENT_CALCULATION_TIME,
        self::INSTALLMENT_CALCULATION_RATE,
    ];

    /**
     * Genders.
     */
    const GENDER_MALE = 'M';
    const GENDER_FEMALE = 'F';

    /**
     * Ratepay request configuration.
     */
    const RATEPAY_REQUEST_VERSION = '1.0';
    const RATEPAY_REQUEST_XMLNS_URN = 'urn://www.ratepay.com/payment/1_0';

    /**
     * Monolog logger configuration.
     */
    const LOGGER_STREAM_OUTPUT = APPLICATION_ROOT_DIR . '/data/log/ratepay.log';

    /**
     * Path to bundle glossary file.
     */
    const GLOSSARY_FILE_PATH = 'Business/Internal/glossary.yml';
}
