<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Sales;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface SalesConstants
{
    /**
     * @var string
     */
    public const NAME_ORDER_REFERENCE = 'OrderReference';
    /**
     * @var string
     */
    public const ENVIRONMENT_PREFIX = 'ENVIRONMENT_PREFIX';

    /**
     * Specification:
     * - Mapping payment methods to statemachine
     * - Shared config: don't change its name: PAYMENT_METHOD_STATEMACHINE_MAPPING
     * - Returns a map of the payment methods and state machine's processes names.
     *
     * @api
     *
     * @example The format of returned array is:
     * [
     *    'PAYMENT_METHOD_1' => 'StateMachineProcess_1',
     *    'PAYMENT_METHOD_2' => 'StateMachineProcess_2',
     * ]
     * @var string
     */
    public const PAYMENT_METHOD_STATEMACHINE_MAPPING = 'PAYMENT_METHOD_STATEMACHINE_MAPPING';
}
