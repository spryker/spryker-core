<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Sales;

interface SalesConstants
{
    const NAME_ORDER_REFERENCE = 'OrderReference';
    const ENVIRONMENT_PREFIX = 'ENVIRONMENT_PREFIX';

    /**
     * Specification:
     * - Mapping payment methods to statemachine
     * - Shared config: don't change its name: PAYMENT_METHOD_STATEMACHINE_MAPPING
     *
     * @api
     */
    const PAYMENT_METHOD_STATEMACHINE_MAPPING = 'PAYMENT_METHOD_STATEMACHINE_MAPPING';
}
