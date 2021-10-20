<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Http;

use Spryker\Glue\Kernel\AbstractBundleConfig;
use Spryker\Shared\Http\HttpConstants;

/**
 * @method \Spryker\Shared\Http\HttpConfig getSharedConfig()
 */
class HttpConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const DEFAULT_REQUEST_HTTP_PORT = 80;

    /**
     * @var int
     */
    protected const DEFAULT_REQUEST_HTTPS_PORT = 443;

    /**
     * @api
     *
     * @return int
     */
    public function getHttpPort(): int
    {
        return $this->get(HttpConstants::GLUE_HTTP_PORT, static::DEFAULT_REQUEST_HTTP_PORT);
    }

    /**
     * @api
     *
     * @return int
     */
    public function getHttpsPort(): int
    {
        return $this->get(HttpConstants::GLUE_HTTPS_PORT, static::DEFAULT_REQUEST_HTTPS_PORT);
    }

    /**
     * @api
     *
     * @return int
     */
    public function getTrustedHeaderSet(): int
    {
        return $this->getSharedConfig()->getTrustedHeaderSet();
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getTrustedProxies(): array
    {
        return $this->get(HttpConstants::GLUE_TRUSTED_PROXIES, []);
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getTrustedHosts(): array
    {
        return $this->get(HttpConstants::GLUE_TRUSTED_HOSTS, []);
    }

    /**
     * @api
     *
     * @return bool
     */
    public function isStrictTransportSecurityEnabled(): bool
    {
        return $this->get(HttpConstants::GLUE_HTTP_STRICT_TRANSPORT_SECURITY_ENABLED, false);
    }

    /**
     * @phpstan-return array<string, mixed>
     *
     * @api
     *
     * @return array
     */
    public function getStrictTransportSecurityConfig(): array
    {
        return $this->get(HttpConstants::GLUE_HTTP_STRICT_TRANSPORT_SECURITY_CONFIG, []);
    }
}
