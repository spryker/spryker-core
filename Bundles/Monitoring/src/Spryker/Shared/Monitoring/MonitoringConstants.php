<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Monitoring;

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
    const IGNORABLE_TRANSACTIONS = 'MONITORING_IGNORABLE_TRANSACTIONS';
}
