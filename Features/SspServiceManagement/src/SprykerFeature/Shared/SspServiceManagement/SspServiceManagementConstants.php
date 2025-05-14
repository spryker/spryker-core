<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Shared\SspServiceManagement;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
class SspServiceManagementConstants
{
    /**
     * Specification
     * - Constant is used to determine payment method by statemachine process.
     *
     * @api
     *
     * @var string
     */
    public const PAYMENT_METHOD_STATEMACHINE_MAPPING = 'SSP_SERVICE_MANAGEMENT:PAYMENT_METHOD_STATEMACHINE_MAPPING';
}
