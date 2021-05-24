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
    public const NAME_ORDER_REFERENCE = 'OrderReference';
    public const ENVIRONMENT_PREFIX = 'ENVIRONMENT_PREFIX';
    public const NAME_VISIBLE_VIOLATIONS = 'visible';
    public const NAME_IGNORED_VIOLATIONS = 'ignored';
    public const VIOLATION_FIELD_NAME_DESCRIPTION = 'description';
    public const VIOLATION_FIELD_NAME_RULESET = 'ruleset';
    public const VIOLATION_FIELD_NAME_RULE = 'rule';
    public const VIOLATION_FIELD_NAME_PRIORITY = 'priority';
    public const VIOLATION_FIELD_NAME_FILENAME = 'fileName';

    /**
     * Specification:
     * - Mapping payment methods to statemachine
     * - Shared config: don't change its name: PAYMENT_METHOD_STATEMACHINE_MAPPING
     *
     * @api
     */
    public const PAYMENT_METHOD_STATEMACHINE_MAPPING = 'PAYMENT_METHOD_STATEMACHINE_MAPPING';
}
