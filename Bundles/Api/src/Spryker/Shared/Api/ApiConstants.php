<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Api;

use Spryker\Zed\Api\Communication\Plugin\BlacklistServerVariableFilterStrategy;
use Spryker\Zed\Api\Communication\Plugin\CallbackServerVariableFilterStrategy;
use Spryker\Zed\Api\Communication\Plugin\WhitelistServerVariableFilterStrategy;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface ApiConstants
{
    /**
     * Specification:
     * - Sets which filtering strategy will be used in ApiControllerListenerPlugin::filterServerData()
     *
     * Example:
     *
     * $config[ApiConstants::ENV_SERVER_VARIABLE_FILTER_STRATEGY] = ApiConstants::ENV_SERVER_VARIABLE_FILTER_STRATEGY_WHITELIST;
     *
     * @api
     */
    public const ENV_SERVER_VARIABLE_FILTER_STRATEGY = 'API:ENV_SERVER_VARIABLE_FILTER_STRATEGY';

    /**
     * Specification:
     * - Use this as a value for filtering strategy. This sets White List strategy.
     * - If selected, then array of allowed Server Variables should be set in $config[ApiConstants::ENV_SERVER_VARIABLE_WHITELIST]
     *
     * @api
     */
    public const ENV_SERVER_VARIABLE_FILTER_STRATEGY_WHITELIST = 'API:ENV_SERVER_VARIABLE_FILTER_STRATEGY_WHITELIST';
    /**
     * Specification:
     * - Use this as a value for filtering strategy. This sets Black List strategy.
     * - If selected, then array of disallowed Server Variables should be set in $config[ApiConstants::ENV_SERVER_VARIABLE_BLACKLIST]
     *
     * @api
     */
    public const ENV_SERVER_VARIABLE_FILTER_STRATEGY_BLACKLIST = 'API:ENV_SERVER_VARIABLE_FILTER_STRATEGY_BLACKLIST';

    /**
     * Specification:
     * - Use this as a value for filtering strategy. This sets Custom strategy of your Callback function.
     * - If selected, then Callable should be specified in $config[ApiConstants::ENV_SERVER_VARIABLE_CALLBACK]
     *
     * @api
     */
    public const ENV_SERVER_VARIABLE_FILTER_STRATEGY_CALLBACK = 'API:ENV_SERVER_VARIABLE_FILTER_STRATEGY_CALLBACK';

    /**
     * Specification:
     * - Array with allowed names of Server Variables
     * - Used only if Whitelist strategy is set in config[ApiConstants::ENV_SERVER_VARIABLE_FILTER_STRATEGY]
     *
     * Example:
     *
     * $config[\Spryker\Shared\Api\ApiConstants::ENV_SERVER_VARIABLE_WHITELIST] = [
     *  'REQUEST_URI'
     * ];
     *
     * @api
     */
    public const ENV_SERVER_VARIABLE_WHITELIST = 'API:ENV_SERVER_VARIABLE_WHITELIST';
    /**
     * Specification:
     * - Array with disallowed names of Server Variables
     * - Used only if Whitelist strategy is set in config[ApiConstants::ENV_SERVER_VARIABLE_FILTER_STRATEGY]
     *
     * Example:
     *
     * $config[\Spryker\Shared\Api\ApiConstants::ENV_SERVER_VARIABLE_WHITELIST] = [
     *  'REQUEST_URI'
     * ];
     *
     * @api
     */
    public const ENV_SERVER_VARIABLE_BLACKLIST = 'API:ENV_SERVER_VARIABLE_BLACKLIST';

    /**
     * Specification:
     * - Callable callback function which can filter keys or mask some sensitive data
     *   where array of Server Variables will be passed as an argument and array expected as return value
     * - Used only if Callback strategy is set in config[ApiConstants::ENV_SERVER_VARIABLE_FILTER_STRATEGY]
     *
     * Example:
     *
     * $config[\Spryker\Shared\Api\ApiConstants::SERVER_VARIABLE_CALLBACK] = function($serverVariables){
     *    foreach ($serverVariables as $key => $value) {
     *      if (preg_match('~^HTTP_~', $key)) {
     *          unset ($serverVariables[$key]);
     *      }
     *    }
     *    return $serverVariables;
     * };
     *
     * @api
     */
    public const ENV_SERVER_VARIABLE_CALLBACK = 'API:ENV_SERVER_VARIABLE_CALLBACK';

    public const ENV_SERVER_VARIABLE_STRATEGY_FILTERER_MAP = [
        self::ENV_SERVER_VARIABLE_FILTER_STRATEGY_WHITELIST => WhitelistServerVariableFilterStrategy::class,
        self::ENV_SERVER_VARIABLE_FILTER_STRATEGY_BLACKLIST => BlacklistServerVariableFilterStrategy::class,
        self::ENV_SERVER_VARIABLE_FILTER_STRATEGY_CALLBACK => CallbackServerVariableFilterStrategy::class,
    ];
}
