<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Api;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface ApiConstants
{
    public const SERVER_VARIABLE_FILTER_STRATEGY = 'SERVER_VARIABLE_FILTER_STRATEGY';
    public const SERVER_VARIABLE_FILTER_STRATEGY_WHITELIST = 'SERVER_VARIABLE_FILTER_STRATEGY_WHITELIST';
    public const SERVER_VARIABLE_FILTER_STRATEGY_BLACKLIST = 'SERVER_VARIABLE_FILTER_STRATEGY_BLACKLIST';
    public const SERVER_VARIABLE_FILTER_STRATEGY_CALLBACK = 'SERVER_VARIABLE_FILTER_STRATEGY_CALLBACK';

    public const SERVER_VARIABLE_WHITELIST = 'SERVER_VARIABLE_WHITELIST';
    public const SERVER_VARIABLE_BLACKLIST = 'SERVER_VARIABLE_BLACKLIST';
    public const SERVER_VARIABLE_CALLBACK = 'SERVER_VARIABLE_CALLBACK';
}
