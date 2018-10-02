<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\NewRelic;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface NewRelicConstants
{
    /**
     * Specification:
     * - NewRelic api key.
     *
     * @api
     */
    public const NEWRELIC_API_KEY = 'NEWRELIC_API_KEY';

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
    public const IGNORABLE_TRANSACTIONS = 'IGNORABLE_TRANSACTIONS';
}
