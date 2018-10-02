<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api;

class Constants
{
    /**
     * Request model types.
     */
    public const REQUEST_MODEL_PROFILE = 'PROFILE_REQUEST';

    public const REQUEST_MODEL_PAYMENT_INIT = 'PAYMENT_INIT';
    public const REQUEST_MODEL_PAYMENT_REQUEST = 'PAYMENT_REQUEST';
    public const REQUEST_MODEL_PAYMENT_CONFIRM = 'PAYMENT_CONFIRM';
    public const REQUEST_MODEL_PAYMENT_CHANGE = 'PAYMENT_CHANGE';
    public const REQUEST_MODEL_DELIVER_CONFIRM = 'CONFIRMATION_DELIVER';
    public const REQUEST_MODEL_PAYMENT_CANCEL = 'PAYMENT_CANCEL';
    public const REQUEST_MODEL_PAYMENT_REFUND = 'PAYMENT_REFUND';
    public const REQUEST_MODEL_CONFIGURATION_REQUEST = 'CONFIGURATION_REQUEST';
    public const REQUEST_MODEL_CALCULATION_REQUEST = 'CALCULATION_REQUEST';

    public const REQUEST_HEADER_CONTENT_TYPE = 'text/xml; charset=UTF8';

    public const REQUEST_MODEL_ADDRESS_TYPE_BILLING = 'BILLING';
    public const REQUEST_MODEL_ADDRESS_TYPE_DELIVERY = 'DELIVERY';
    public const REQUEST_MODEL_ADDRESS_TYPE_REGISTRY = 'REGISTRY';

    public const REQUEST_CODE_SUCCESS_MATRIX = [
        self::REQUEST_MODEL_PAYMENT_INIT => 350,
        self::REQUEST_MODEL_PAYMENT_CONFIRM => 400,
        self::REQUEST_MODEL_PAYMENT_REQUEST => 402,
        self::REQUEST_MODEL_PAYMENT_CHANGE => 403,
        self::REQUEST_MODEL_DELIVER_CONFIRM => 404,
        self::REQUEST_MODEL_CONFIGURATION_REQUEST => 500,
        self::REQUEST_MODEL_CALCULATION_REQUEST => 502,
        self::REQUEST_MODEL_PROFILE => 500,
        self::REQUEST_MODEL_PAYMENT_REFUND => 403,
        self::REQUEST_MODEL_PAYMENT_CANCEL => 403,
    ];

    /**
     * User Agent of Spryker client.
     */
    public const CLIENT_VERSION = '1.0';
    public const CLIENT_NAME = 'Spryker_RP_DE';
}
