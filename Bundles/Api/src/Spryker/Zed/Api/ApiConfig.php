<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api;

use Spryker\Shared\Api\ApiConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ApiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const ROUTE_PREFIX_API_REST = '/api/rest/';

    /**
     * @var string
     */
    public const FORMAT_TYPE = 'json';

    /**
     * @var string
     */
    public const ACTION_CREATE = 'add';
    /**
     * @var string
     */
    public const ACTION_READ = 'get';
    /**
     * @var string
     */
    public const ACTION_UPDATE = 'update';
    /**
     * @var string
     */
    public const ACTION_DELETE = 'remove';
    /**
     * @var string
     */
    public const ACTION_INDEX = 'find';
    /**
     * @var string
     */
    public const ACTION_OPTIONS = 'options';

    /**
     * @var string
     */
    public const HTTP_METHOD_OPTIONS = 'OPTIONS';
    /**
     * @var string
     */
    public const HTTP_METHOD_GET = 'GET';
    /**
     * @var string
     */
    public const HTTP_METHOD_POST = 'POST';
    /**
     * @var string
     */
    public const HTTP_METHOD_PATCH = 'PATCH';
    /**
     * @var string
     */
    public const HTTP_METHOD_DELETE = 'DELETE';

    /**
     * @var int
     */
    public const HTTP_CODE_SUCCESS = 200;
    /**
     * @var int
     */
    public const HTTP_CODE_CREATED = 201;
    /**
     * @var int
     */
    public const HTTP_CODE_NO_CONTENT = 204;
    /**
     * @var int
     */
    public const HTTP_CODE_PARTIAL_CONTENT = 206;
    /**
     * @var int
     */
    public const HTTP_CODE_NOT_FOUND = 404;
    /**
     * @var int
     */
    public const HTTP_CODE_NOT_ALLOWED = 405;
    /**
     * @var int
     */
    public const HTTP_CODE_VALIDATION_ERRORS = 422;
    /**
     * @var int
     */
    public const HTTP_CODE_INTERNAL_ERROR = 500;

    /**
     * @api
     *
     * @return int
     */
    public function getLimitPerPage()
    {
        return 20;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getMaxLimitPerPage()
    {
        return 100;
    }

    /**
     * All REST API resources will not be available, routes will be disabled.
     *
     * @api
     *
     * @return bool
     */
    public function isApiEnabled(): bool
    {
        return false;
    }

    /**
     * This returns the base URI to the API
     *
     * Modify if you want to include host and schema/protocol.
     *
     * @api
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
     * @api
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
     * @api
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
     * @api
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
     * @api
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
     * @api
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
     * @api
     *
     * @return array<string>
     */
    public function getSafeHeaderDataKeys(): array
    {
        return [
            'origin',
            'range',
        ];
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getSafeServerDataKeys(): array
    {
        return [
            'REQUEST_URI',
        ];
    }

    /**
     * @api
     *
     * @return bool
     */
    public function isApiDebugEnabled(): bool
    {
        return $this->get(ApiConstants::ENABLE_API_DEBUG, $this->getApiDebugDefaultValue());
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return bool
     */
    protected function getApiDebugDefaultValue(): bool
    {
        return APPLICATION_ENV === 'development';
    }
}
