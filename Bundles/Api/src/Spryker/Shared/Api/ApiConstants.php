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
    public const API_ENV_SERVER_VARIABLE_FILTER_STRATEGY = 'API_ENV_SERVER_VARIABLE_FILTER_STRATEGY';
    public const API_ENV_SERVER_VARIABLE_FILTER_STRATEGY_WHITELIST = 'API_ENV_SERVER_VARIABLE_FILTER_STRATEGY_WHITELIST';
    public const API_ENV_SERVER_VARIABLE_FILTER_STRATEGY_BLACKLIST = 'API_ENV_SERVER_VARIABLE_FILTER_STRATEGY_BLACKLIST';
    public const API_ENV_SERVER_VARIABLE_FILTER_STRATEGY_CALLBACK = 'API_ENV_SERVER_VARIABLE_FILTER_STRATEGY_CALLBACK';

    public const API_ENV_SERVER_VARIABLE_WHITELIST = 'API_ENV_SERVER_VARIABLE_WHITELIST';
    public const API_ENV_SERVER_VARIABLE_BLACKLIST = 'API_ENV_SERVER_VARIABLE_BLACKLIST';
    public const API_ENV_SERVER_VARIABLE_CALLBACK = 'API_ENV_SERVER_VARIABLE_CALLBACK';
}
