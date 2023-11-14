<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication;

use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Spryker\Glue\Kernel\AbstractBundleConfig;
use Spryker\Shared\GlueApplication\GlueApplicationConstants;

class GlueApplicationConfig extends AbstractBundleConfig
{
    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    public const COLLECTION_IDENTIFIER_CURRENT_USER = 'mine';

    /**
     * @api
     *
     * @var string
     */
    public const ERROR_CODE_RESOURCE_NOT_FOUND = '007';

    /**
     * @api
     *
     * @var string
     */
    public const ERROR_MESSAGE_RESOURCE_NOT_FOUND = 'Not found';

    /**
     * @api
     *
     * @var string
     */
    public const ERROR_CODE_METHOD_NOT_FOUND = '008';

    /**
     * @api
     *
     * @var string
     */
    public const ERROR_CODE_PARENT_RESOURCE_NOT_FOUND = '009';

    /**
     * @api
     *
     * @var string
     */
    public const ERROR_CODE_UNSUPPORTED_ACCEPT_FORMAT = '010';

    /**
     * @api
     *
     * @var string
     */
    public const ERROR_MESSAGE_PARENT_RESOURCE_NOT_FOUND = 'Not found';

    /**
     * @api
     *
     * @var string
     */
    public const ERROR_MESSAGE_METHOD_NOT_FOUND = 'Method does not exist';

    /**
     * @api
     *
     * @var string
     */
    public const ERROR_MESSAGE_UNSUPPORTED_ACCEPT_FORMAT = 'Unsupported `Accept` format used.';

    /**
     * @api
     *
     * @var string
     */
    public const ERROR_CODE_UNSUPPORTED_FILTER_FORMAT = '011';

    /**
     * @api
     *
     * @var string
     */
    public const ERROR_MESSAGE_UNSUPPORTED_FILTER_FORMAT = 'Unsupported `Filter` format is used. Please use `filter[resource.property]`';

    /**
     * @var string
     */
    protected const DEFAULT_RESPONSE_FORMAT = 'application/json';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    protected const HEADER_X_FRAME_OPTIONS_VALUE = 'SAMEORIGIN';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    protected const HEADER_CONTENT_SECURITY_POLICY_VALUE = 'frame-ancestors \'self\'';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    protected const HEADER_X_CONTENT_TYPE_OPTIONS_VALUE = 'nosniff';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    protected const HEADER_X_XSS_PROTECTION_VALUE = '1; mode=block';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    protected const HEADER_REFERRER_POLICY_VALUE = 'same-origin';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    protected const HEADER_PERMISSIONS_POLICY_VALUE = '';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var bool
     */
    public const VALIDATE_REQUEST_HEADERS = true;

    /**
     * @api
     *
     * @var string
     */
    public const API_CONTROLLER_CACHE_FILENAME = 'api_controller.cache';

    /**
     * Specification:
     *  - Enables or disables request header validation.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return bool
     */
    public function getValidateRequestHeaders(): bool
    {
        return static::VALIDATE_REQUEST_HEADERS;
    }

    /**
     * Specification:
     *  - Domain name of glue application to build API links.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return string
     */
    public function getGlueDomainName(): string
    {
        return $this->get(GlueApplicationConstants::GLUE_APPLICATION_DOMAIN);
    }

    /**
     * Specification:
     *  - Indicates whether debug of rest is enabled.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return bool
     */
    public function getIsRestDebugEnabled(): bool
    {
        return $this->get(GlueApplicationConstants::GLUE_APPLICATION_REST_DEBUG, false);
    }

    /**
     * Specification:
     *  - Specifies a URI that may access the resources.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return string
     */
    public function getCorsAllowOrigin(): string
    {
        return $this->get(GlueApplicationConstants::GLUE_APPLICATION_CORS_ALLOW_ORIGIN, '');
    }

    /**
     * Specification:
     *  - List of allowed CORS headers.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return array<string>
     */
    public function getCorsAllowedHeaders(): array
    {
        return [
            RequestConstantsInterface::HEADER_ACCEPT,
            RequestConstantsInterface::HEADER_CONTENT_TYPE,
            RequestConstantsInterface::HEADER_CONTENT_LANGUAGE,
            RequestConstantsInterface::HEADER_ACCEPT_LANGUAGE,
            RequestConstantsInterface::HEADER_AUTHORIZATION,
        ];
    }

    /**
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return array<string, string>
     */
    public function getSecurityHeaders(): array
    {
        return [
            'X-Frame-Options' => static::HEADER_X_FRAME_OPTIONS_VALUE,
            'Content-Security-Policy' => static::HEADER_CONTENT_SECURITY_POLICY_VALUE,
            'X-Content-Type-Options' => static::HEADER_X_CONTENT_TYPE_OPTIONS_VALUE,
            'X-XSS-Protection' => static::HEADER_X_XSS_PROTECTION_VALUE,
            'Referrer-Policy' => static::HEADER_REFERRER_POLICY_VALUE,
            'Permissions-policy' => static::HEADER_PERMISSIONS_POLICY_VALUE,
        ];
    }

    /**
     * Specification:
     *  - Indicates whether all relationships should be included in response by default.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return bool
     */
    public function isEagerRelationshipsLoadingEnabled(): bool
    {
        return true;
    }

    /**
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return bool
     */
    public function isDebugModeEnabled(): bool
    {
        return $this->get(GlueApplicationConstants::ENABLE_APPLICATION_DEBUG, false);
    }

    /**
     * Specification:
     * - Overwrite this to true if API version resolving should happen to all endpoints via the first part of the path
     * - e.g /1/resource1 or /v1/resource2 instead of header value
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return bool
     */
    public function getPathVersionResolving(): bool
    {
        return false;
    }

    /**
     * Specification:
     * - Set this to the value you want to be the prefix of the version in the URL (if any)
     * - In the default setting, it will not exist, but if it is set to "v" then all versionable resources will have
     * - a "v" as a prefix to their version in the URL. e.g. /v1/resource
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return string
     */
    public function getPathVersionPrefix(): string
    {
        return '';
    }

    /**
     * Specification:
     * - Official semver regex for matching a semver version, but removed the requirement for patch or minor version
     * - for easier versioning of APIs. API versions do not have patch versions since patches do not change the response type
     *
     * - To overwrite this smoothly, please add a named capturing group called "fullVersion" to your regex that contains
     * - your full semVer version (e.g 1.1 or 1). Otherwise, the first capture group will be taken as full version number
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @see https://semver.org/#is-there-a-suggested-regular-expression-regex-to-check-a-semver-string
     *
     * @return string
     */
    public function getApiVersionResolvingRegex(): string
    {
        return '/^(?P<fullVersion>(0|[1-9]\d*)(\.(0|[1-9]\d*))?)$/';
    }

    /**
     * Specification:
     * - Returns a path that contains the Controllers configuration cache.
     *
     * @api
     *
     * @return string
     */
    public function getControllerCachePath(): string
    {
        return sprintf(
            '%s/src/Generated/%s/Controller/codeBucket%s',
            APPLICATION_ROOT_DIR,
            'Glue',
            APPLICATION_CODE_BUCKET,
        );
    }

    /**
     * Specification:
     * - Returns if current application should call terminate method at the end of the execution flow.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return bool
     */
    public function isTerminationEnabled(): bool
    {
        return false;
    }

    /**
     * Specification:
     * - Lists the route matchers that will run for each ApiApplication.
     *
     * @api
     *
     * @return array<string>
     */
    public function getRouteMatchers(): array
    {
        return [
            'routes',
            'resources',
        ];
    }

    /**
     * Specification:
     * - Returns if current application is in development mode.
     *
     * @api
     *
     * @return bool
     */
    public function isDevelopmentMode(): bool
    {
        return APPLICATION_ENV === 'development' || APPLICATION_ENV === 'docker.dev';
    }

    /**
     * Specification:
     * - Returns a list of route patterns for which cache warmup is allowed if the route is not found in the cache.
     *
     * @api
     *
     * @return array<string>
     */
    public function getRoutePatternsAllowedForCacheWarmUp(): array
    {
        return [];
    }

    /**
     * Specification:
     * - Returns the default response format `application/json` that will be used if none could be negotiated.
     *
     * @api
     *
     * @return string
     */
    public function getDefaultResponseFormat(): string
    {
        return static::DEFAULT_RESPONSE_FORMAT;
    }

    /**
     * Specification:
     * - If true returns a response in format `{...}` according to REST API convention.
     * - If false returns a response in format `[{...}]` not following to REST API convention.
     *
     * @api
     *
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @return bool
     */
    public function isConfigurableResponseEnabled(): bool
    {
        return false;
    }
}
