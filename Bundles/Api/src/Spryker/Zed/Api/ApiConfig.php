<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api;

use Spryker\Zed\Api\Communication\Plugin\BlacklistServerVariableFilterStrategy;
use Spryker\Zed\Api\Communication\Plugin\CallbackServerVariableFilterStrategy;
use Spryker\Zed\Api\Communication\Plugin\WhitelistServerVariableFilterStrategy;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ApiConfig extends AbstractBundleConfig
{
    const ROUTE_PREFIX_API_REST = '/api/rest/';

    const FORMAT_TYPE = 'json';

    const ACTION_CREATE = 'add';
    const ACTION_READ = 'get';
    const ACTION_UPDATE = 'update';
    const ACTION_DELETE = 'remove';
    const ACTION_INDEX = 'find';
    const ACTION_OPTIONS = 'options';

    const HTTP_METHOD_OPTIONS = 'OPTIONS';
    const HTTP_METHOD_GET = 'GET';
    const HTTP_METHOD_POST = 'POST';
    const HTTP_METHOD_PATCH = 'PATCH';
    const HTTP_METHOD_DELETE = 'DELETE';

    const HTTP_CODE_SUCCESS = 200;
    const HTTP_CODE_CREATED = 201;
    const HTTP_CODE_NO_CONTENT = 204;
    const HTTP_CODE_PARTIAL_CONTENT = 206;
    const HTTP_CODE_NOT_FOUND = 404;
    const HTTP_CODE_NOT_ALLOWED = 405;
    const HTTP_CODE_VALIDATION_ERRORS = 422;
    const HTTP_CODE_INTERNAL_ERROR = 500;

    public const SERVER_VARIABLE_FILTER_STRATEGY_WHITELIST = 'SERVER_VARIABLE_FILTER_STRATEGY_WHITELIST';
    public const SERVER_VARIABLE_FILTER_STRATEGY_BLACKLIST = 'SERVER_VARIABLE_FILTER_STRATEGY_BLACKLIST';
    public const SERVER_VARIABLE_FILTER_STRATEGY_CALLBACK = 'SERVER_VARIABLE_FILTER_STRATEGY_CALLBACK';

    /**
     * @return int
     */
    public function getLimitPerPage()
    {
        return 20;
    }

    /**
     * @return int
     */
    public function getMaxLimitPerPage()
    {
        return 100;
    }

    /**
     * This returns the base URI to the API
     *
     * Modify if you want to include host and schema/protocol.
     *
     * @return string
     */
    public function getBaseUri()
    {
        return static::ROUTE_PREFIX_API_REST;
    }

    /**
     * Defines HTTP methods for an item request. OPTIONS are added automatically.
     *
     * @return array
     */
    public function getHttpMethodsForItem()
    {
        return [
            static::HTTP_METHOD_GET,
            static::HTTP_METHOD_PATCH,
            static::HTTP_METHOD_DELETE,
        ];
    }

    /**
     * Defines HTTP methods for a collection request. OPTIONS are added automatically.
     *
     * @return array
     */
    public function getHttpMethodsForCollection()
    {
        return [
            static::HTTP_METHOD_GET,
            static::HTTP_METHOD_POST,
        ];
    }

    /**
     * Defines the CORS Access-Control-Allowed-Origin header.
     *
     * Use null to always set to current "Origin" given, or "*" for all.
     * You can also specify concrete URLs, e.g. "http://example.org".
     *
     * @return string|null
     */
    public function getAllowedOrigin()
    {
        return null;
    }

    /**
     * Defines the CORS Access-Control-Request-Headers header.
     *
     * You can also set to custom ones, e.g. "X-PINGOTHER, Content-Type"
     *
     * @return string
     */
    public function getAllowedRequestHeaders()
    {
        return 'origin, x-requested-with, accept';
    }

    /**
     * Defines the CORS Access-Control-Request-Methods types.
     *
     * @return array
     */
    public function getAllowedRequestMethods()
    {
        $methodsForItem = $this->getHttpMethodsForItem();
        $methodsForCollection = $this->getHttpMethodsForCollection();
        $methods = array_merge($methodsForItem, $methodsForCollection);

        return array_unique($methods);
    }

    /**
     * Stores current strategy for filtering of Server Variable
     *
     * @return string
     */
    public function getCurrentServerVariablesFilterStrategy(): string
    {
        return static::SERVER_VARIABLE_FILTER_STRATEGY_WHITELIST;
    }

    /**
     * Defines set of available strategies for filtering of Server Variables
     *
     * @return array
     */
    public function getServerVariablesFilterStrategyFiltererMap(): array
    {
        return [
            self::SERVER_VARIABLE_FILTER_STRATEGY_WHITELIST => WhitelistServerVariableFilterStrategy::class,
            self::SERVER_VARIABLE_FILTER_STRATEGY_BLACKLIST => BlacklistServerVariableFilterStrategy::class,
            self::SERVER_VARIABLE_FILTER_STRATEGY_CALLBACK => CallbackServerVariableFilterStrategy::class,
        ];
    }

    /**
     * @return array
     */
    public function getServerVariablesWhitelist(): array
    {
        return ['REQUEST_URI'];
    }

    /**
     * @return array
     */
    public function getServerVariablesBlacklist(): array
    {
        return [];
    }

    /**
     * @return callable
     */
    public function getServerVariablesCallback(): callable
    {
        return function ($serverVariables) {
            return $serverVariables;
        };
    }
}
