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
     * @var string
     */
    public const COLLECTION_IDENTIFIER_CURRENT_USER = 'mine';

    /**
     * @var string
     */
    protected const HEADER_X_FRAME_OPTIONS_VALUE = 'SAMEORIGIN';

    /**
     * @var string
     */
    protected const HEADER_CONTENT_SECURITY_POLICY_VALUE = 'frame-ancestors \'self\'';

    /**
     * @var string
     */
    protected const HEADER_X_CONTENT_TYPE_OPTIONS_VALUE = 'nosniff';

    /**
     * @var string
     */
    protected const HEADER_X_XSS_PROTECTION_VALUE = '1; mode=block';

    /**
     * @var string
     */
    protected const HEADER_REFERRER_POLICY_VALUE = 'same-origin';

    /**
     * @var string
     */
    protected const HEADER_PERMISSIONS_POLICY_VALUE = '';

    /**
     * @var bool
     */
    public const VALIDATE_REQUEST_HEADERS = true;

    /**
     * Specification:
     *  - Enables or disables request header validation.
     *
     * @api
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
     * @return bool
     */
    public function isEagerRelationshipsLoadingEnabled(): bool
    {
        return true;
    }

    /**
     * @api
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
     * @see https://semver.org/#is-there-a-suggested-regular-expression-regex-to-check-a-semver-string
     *
     * @return string
     */
    public function getApiVersionResolvingRegex(): string
    {
        return '/^(?P<fullVersion>(0|[1-9]\d*)(\.(0|[1-9]\d*))?)$/';
    }
}
