<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Monitoring;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface MonitoringConstants
{
    /**
     * Specification:
     * - Array of transactions which should be ignored.
     *
     * Example:
     *
     * $config[MonitoringConstants::IGNORABLE_TRANSACTIONS] = [
     *      '_profiler',
     *      'foo/bar/baz'
     * ];
     *
     * @api
     */
    public const IGNORABLE_TRANSACTIONS = 'MONITORING:IGNORABLE_TRANSACTIONS';
}
