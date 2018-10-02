<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Ratepay;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface RatepayConstants
{
    public const PROVIDER_NAME = 'Ratepay';

    public const PAYMENT_METHOD_INVOICE = 'ratepayInvoice';
    public const PAYMENT_METHOD_ELV = 'ratepayElv';
    public const PAYMENT_METHOD_INSTALLMENT = 'ratepayInstallment';
    public const PAYMENT_METHOD_PREPAYMENT = 'ratepayPrepayment';

    public const INVOICE = 'INVOICE';
    public const ELV = 'ELV';
    public const INSTALLMENT = 'INSTALLMENT';
    public const PREPAYMENT = 'PREPAYMENT';

    public const PROFILE_ID = 'RATEPAY_PROFILE_ID';
    public const SECURITY_CODE = 'RATEPAY_SECURITY_CODE';
    public const SNIPPET_ID = 'RATEPAY_SNIPPET_ID';
    public const SHOP_ID = 'RATEPAY_SHOP_ID';
    public const SYSTEM_ID = 'RATEPAY_SYSTEM_ID';

    /**
     * API modes urls.
     */
    public const API_URL = 'RATEPAY_API_URL';

    /**
     * Payment submethods.
     */
    public const METHOD_INVOICE = 'INVOICE';
    public const METHOD_ELV = 'ELV';
    public const METHOD_PREPAYMENT = 'PREPAYMENT';
    public const METHOD_INSTALLMENT = 'INSTALLMENT';

    public const PAYMENT_METHODS_MAP = [
        self::METHOD_INVOICE => 'ratepayInvoice',
        self::METHOD_ELV => 'ratepayElv',
        self::METHOD_INSTALLMENT => 'ratepayInstallment',
        self::METHOD_PREPAYMENT => 'ratepayPrepayment',
    ];

    /**
     * Service method
     */
    public const METHOD_SERVICE = 'SERVICE';

    /**
     * Installment debit pay type.
     */
    public const DEBIT_PAY_TYPE_DIRECT_DEBIT = 'DIRECT-DEBIT';
    public const DEBIT_PAY_TYPE_BANK_TRANSFER = 'BANK-TRANSFER';
    public const DEBIT_PAY_TYPES = [
        self::DEBIT_PAY_TYPE_DIRECT_DEBIT,
        self::DEBIT_PAY_TYPE_BANK_TRANSFER,
    ];

    /**
     * Installment calculator types.
     */
    public const INSTALLMENT_CALCULATION_TIME = 'calculation-by-time';
    public const INSTALLMENT_CALCULATION_RATE = 'calculation-by-rate';
    public const INSTALLMENT_CALCULATION_TYPES = [
        self::INSTALLMENT_CALCULATION_TIME,
        self::INSTALLMENT_CALCULATION_RATE,
    ];

    /**
     * Genders.
     */
    public const GENDER_MALE = 'M';
    public const GENDER_FEMALE = 'F';

    /**
     * Ratepay request configuration.
     */
    public const RATEPAY_REQUEST_VERSION = '1.0';
    public const RATEPAY_REQUEST_XMLNS_URN = 'urn://www.ratepay.com/payment/1_0';

    /**
     * Monolog logger configuration.
     */
    public const LOGGER_STREAM_OUTPUT = APPLICATION_ROOT_DIR . '/data/log/ratepay.log';

    /**
     * Path to bundle glossary file.
     */
    public const GLOSSARY_FILE_PATH = 'Business/Internal/glossary.yml';
}
