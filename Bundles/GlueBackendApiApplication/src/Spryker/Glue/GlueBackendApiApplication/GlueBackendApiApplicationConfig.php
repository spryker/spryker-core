<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication;

use Spryker\Glue\Kernel\AbstractBundleConfig;
use Spryker\Shared\GlueBackendApiApplication\GlueBackendApiApplicationConstants;
use Symfony\Component\Routing\Generator\CompiledUrlGenerator;
use Symfony\Component\Routing\Matcher\CompiledUrlMatcher;

class GlueBackendApiApplicationConfig extends AbstractBundleConfig
{
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
     * @var string
     */
    protected const HEADER_ACCEPT = 'accept';

    /**
     * @var string
     */
    protected const HEADER_ACCEPT_LANGUAGE = 'accept-language';

    /**
     * @var string
     */
    protected const HEADER_CONTENT_TYPE = 'content-type';

    /**
     * @var string
     */
    protected const HEADER_CONTENT_LANGUAGE = 'content-language';

    /**
     * @var string
     */
    protected const HEADER_AUTHORIZATION = 'authorization';

    /**
     * @uses \Spryker\Zed\Oauth\OauthConfig::GENERATED_FULL_FILE_NAME
     *
     * @var string
     */
    protected const GENERATED_FULL_FILE_NAME = '/Generated/Zed/Oauth/GlueScopesCache/glue_scopes_cache.yml';

    /**
     * @var string
     */
    protected const HEADER_NAME_ACCESS_CONTROL_ALLOW_ORIGIN = 'Access-Control-Allow-Origin';

    /**
     * Specification:
     * - Returns the host that the Backend API application serves
     *
     * @api
     *
     * @return string
     */
    public function getBackendApiApplicationHost(): string
    {
        return $this->get(GlueBackendApiApplicationConstants::GLUE_BACKEND_API_HOST, '');
    }

    /**
     * Specification:
     * - Configures if api application should output debug statements
     *
     * @api
     *
     * @return bool
     */
    public function isDebugModeEnabled(): bool
    {
        return (bool)$this->get(
            GlueBackendApiApplicationConstants::ENABLE_APPLICATION_DEBUG,
            false,
        );
    }

    /**
     * Specification:
     * - Return the list of security headers returned with each request to the backend API.
     *
     * @api
     *
     * @return array<string, string>
     */
    public function getSecurityHeaders(): array
    {
        $securityHeaders = [
            'X-Frame-Options' => static::HEADER_X_FRAME_OPTIONS_VALUE,
            'Content-Security-Policy' => static::HEADER_CONTENT_SECURITY_POLICY_VALUE,
            'X-Content-Type-Options' => static::HEADER_X_CONTENT_TYPE_OPTIONS_VALUE,
            'X-XSS-Protection' => static::HEADER_X_XSS_PROTECTION_VALUE,
            'Referrer-Policy' => static::HEADER_REFERRER_POLICY_VALUE,
            'Permissions-policy' => static::HEADER_PERMISSIONS_POLICY_VALUE,
        ];

        return $this->addAccessControlAllowOriginHeader($securityHeaders);
    }

    /**
     * Specification:
     * - List of allowed CORS headers.
     *
     * @api
     *
     * @return array<string>
     */
    public function getCorsAllowedHeaders(): array
    {
        return [
            static::HEADER_ACCEPT,
            static::HEADER_CONTENT_TYPE,
            static::HEADER_CONTENT_LANGUAGE,
            static::HEADER_ACCEPT_LANGUAGE,
            static::HEADER_AUTHORIZATION,
        ];
    }

    /**
     * Specification:
     * - Returns a Router configuration which makes use of a Router cache.
     *
     * @api
     *
     * @see \Symfony\Component\Routing\Router::setOptions()
     *
     * @return array<string, mixed>
     */
    public function getRouterConfiguration(): array
    {
        return [
            'cache_dir' => $this->getCachePathIfCacheEnabled(),
            'generator_class' => CompiledUrlGenerator::class,
            'matcher_class' => CompiledUrlMatcher::class,
        ];
    }

    /**
     * @api
     *
     * @return string
     */
    public function getGeneratedFullFileNameForCollectedScopes(): string
    {
        return APPLICATION_SOURCE_DIR . static::GENERATED_FULL_FILE_NAME;
    }

    /**
     * @return string
     */
    protected function getCorsAllowOrigin(): string
    {
        return $this->get(GlueBackendApiApplicationConstants::GLUE_BACKEND_CORS_ALLOW_ORIGIN, '');
    }

    /**
     * @return string|null
     */
    protected function getCachePathIfCacheEnabled(): ?string
    {
        if ($this->get(GlueBackendApiApplicationConstants::GLUE_IS_CACHE_ENABLED, true)) {
            return $this->get(GlueBackendApiApplicationConstants::GLUE_CACHE_PATH, $this->getDefaultRouterCachePath());
        }

        return null;
    }

    /**
     * @return string
     */
    protected function getDefaultRouterCachePath(): string
    {
        $projectNamespaces = implode('/', $this->get(GlueBackendApiApplicationConstants::PROJECT_NAMESPACES));

        return sprintf(
            '%s/src/Generated/%s/Router/codeBucket%s/%s',
            APPLICATION_ROOT_DIR,
            'GlueBackend',
            APPLICATION_CODE_BUCKET,
            $projectNamespaces,
        );
    }

    /**
     * @param array<string, string> $securityHeaders
     *
     * @return array<string, string>
     */
    protected function addAccessControlAllowOriginHeader(array $securityHeaders): array
    {
        if ($this->getCorsAllowOrigin() === '') {
            return $securityHeaders;
        }

        $securityHeaders[static::HEADER_NAME_ACCESS_CONTROL_ALLOW_ORIGIN] = $this->getCorsAllowOrigin();

        return $securityHeaders;
    }
}
