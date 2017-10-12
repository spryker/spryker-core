<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\NewRelic;

interface NewRelicConstants
{
    /**
     * Specification:
     * - NewRelic api key.
     *
     * @api
     */
    const NEWRELIC_API_KEY = 'NEWRELIC_API_KEY';

    /**
     * Specification:
     * - Array of transactions which should be ignored.
     *
     * Example:
     *
     * $config[NewRelicConstants::IGNORABLE_TRANSACTIONS] = [
     *      '_profiler',
     *      'foo/bar/baz'
     * ];
     *
     * @api
     */
    const IGNORABLE_TRANSACTIONS = 'IGNORABLE_TRANSACTIONS';
}
