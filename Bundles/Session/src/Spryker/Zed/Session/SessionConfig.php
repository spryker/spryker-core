<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Session;

use Spryker\Shared\Session\SessionConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class SessionConfig extends AbstractBundleConfig
{
    public const PROTOCOL_TCP = 'tcp';

    public const DATA_SOURCE_NAME_TEMPLATE_TCP = 'tcp://[host]:[port]?database=[database][authFragment]';
    public const AUTH_FRAGMENT_TEMPLATE_TCP = '&password=%s';

    public const DATA_SOURCE_NAME_TEMPLATE_REDIS = 'redis://[authFragment][host]:[port]/[database]';
    public const AUTH_FRAGMENT_TEMPLATE_REDIS = ':%s@';

    /**
     * Default Redis database number
     */
    public const DEFAULT_REDIS_DATABASE = 0;

    /**
     * @return array
     */
    public function getSessionStorageOptions()
    {
        $sessionStorageOptions = [
            'name' => str_replace('.', '-', $this->get(SessionConstants::ZED_SESSION_COOKIE_NAME)),
            'cookie_lifetime' => $this->getSessionCookieTimeToLive(),
            'cookie_secure' => $this->secureCookie(),
            'cookie_domain' => $this->getSessionCookieDomain(),
            'cookie_path' => $this->getSessionCookiePath(),
            'cookie_httponly' => true,
            'use_only_cookies' => true,
        ];

        return $sessionStorageOptions;
    }

    /**
     * Projects should use `SessionConstants::ZED_SESSION_COOKIE_TIME_TO_LIVE`. If they don't have it in
     * their config we will use the existing `SessionConstants::ZED_SESSION_TIME_TO_LIVE` as default value.
     *
     * @return int
     */
    private function getSessionCookieTimeToLive()
    {
        return (int)$this->get(SessionConstants::ZED_SESSION_COOKIE_TIME_TO_LIVE, $this->get(SessionConstants::ZED_SESSION_TIME_TO_LIVE));
    }

    /**
     * @return bool
     */
    protected function secureCookie()
    {
        return ($this->get(SessionConstants::ZED_SESSION_COOKIE_SECURE, true) && $this->get(SessionConstants::ZED_SSL_ENABLED, true));
    }

    /**
     * @return string
     */
    protected function getSessionCookieDomain(): string
    {
        return $this->get(SessionConstants::ZED_SESSION_COOKIE_DOMAIN, '');
    }

    /**
     * @return string
     */
    protected function getSessionCookiePath(): string
    {
        return $this->get(SessionConstants::ZED_SESSION_COOKIE_PATH, '/');
    }

    /**
     * @return string
     */
    public function getConfiguredSessionHandlerNameZed()
    {
        return $this->get(SessionConstants::ZED_SESSION_SAVE_HANDLER);
    }

    /**
     * @return string
     */
    public function getConfiguredSessionHandlerNameYves()
    {
        return $this->get(SessionConstants::YVES_SESSION_SAVE_HANDLER);
    }

    /**
     * @return int
     */
    public function getSessionLifeTime()
    {
        return (int)$this->get(SessionConstants::ZED_SESSION_TIME_TO_LIVE);
    }

    /**
     * @deprecated Use `Spryker\Shared\SessionExtension\Dependency\Plugin\SessionHandlerProviderPluginInterface` instead.
     *
     * @return array|string
     */
    public function getSessionHandlerRedisConnectionParametersZed()
    {
        $connectionConfiguration = $this->get(SessionConstants::ZED_SESSION_PREDIS_CLIENT_CONFIGURATION, []);

        if ($connectionConfiguration) {
            return $connectionConfiguration;
        }

        return $this->getSessionHandlerRedisDataSourceNameZed();
    }

    /**
     * @deprecated Use `Spryker\Shared\SessionExtension\Dependency\Plugin\SessionHandlerProviderPluginInterface` instead.
     *
     * @return array
     */
    public function getSessionHandlerRedisConnectionOptionsZed(): array
    {
        return $this->get(SessionConstants::ZED_SESSION_PREDIS_CLIENT_OPTIONS, []);
    }

    /**
     * @deprecated Use `Spryker\Shared\SessionExtension\Dependency\Plugin\SessionHandlerProviderPluginInterface` instead.
     *
     * @return string
     */
    public function getSessionHandlerRedisDataSourceNameZed()
    {
        return $this->buildDataSourceName(
            $this->get(SessionConstants::ZED_SESSION_REDIS_PROTOCOL),
            $this->get(SessionConstants::ZED_SESSION_REDIS_HOST),
            $this->get(SessionConstants::ZED_SESSION_REDIS_PORT),
            $this->get(SessionConstants::ZED_SESSION_REDIS_DATABASE, static::DEFAULT_REDIS_DATABASE),
            $this->get(SessionConstants::ZED_SESSION_REDIS_PASSWORD, false)
        );
    }

    /**
     * @deprecated Use `Spryker\Shared\SessionExtension\Dependency\Plugin\SessionHandlerProviderPluginInterface` instead.
     *
     * @param string $protocol
     * @param string $host
     * @param int $port
     * @param int $database
     * @param string $password
     *
     * @return string
     */
    protected function buildDataSourceName($protocol, $host, $port, $database, $password)
    {
        $authFragmentTemplate = $this->getAuthFragmentTemplate($protocol);
        $dataSourceNameTemplate = $this->getDataSourceNameTemplate($protocol);
        $authFragment = '';
        if ($password) {
            $authFragment = sprintf($authFragmentTemplate, $password);
        }

        $dataSourceNameElements = [
            '[host]' => $host,
            '[port]' => $port,
            '[database]' => $database,
            '[authFragment]' => $authFragment,
        ];

        return str_replace(
            array_keys($dataSourceNameElements),
            array_values($dataSourceNameElements),
            $dataSourceNameTemplate
        );
    }

    /**
     * @deprecated Use `Spryker\Shared\SessionExtension\Dependency\Plugin\SessionHandlerProviderPluginInterface` instead.
     *
     * @param string $protocol
     *
     * @return string
     */
    protected function getAuthFragmentTemplate($protocol)
    {
        return ($protocol === static::PROTOCOL_TCP) ? static::AUTH_FRAGMENT_TEMPLATE_TCP : static::AUTH_FRAGMENT_TEMPLATE_REDIS;
    }

    /**
     * @deprecated Use `Spryker\Shared\SessionExtension\Dependency\Plugin\SessionHandlerProviderPluginInterface` instead.
     *
     * @param string $protocol
     *
     * @return string
     */
    protected function getDataSourceNameTemplate($protocol)
    {
        return ($protocol === static::PROTOCOL_TCP) ? static::DATA_SOURCE_NAME_TEMPLATE_TCP : static::DATA_SOURCE_NAME_TEMPLATE_REDIS;
    }

    /**
     * @deprecated Use `Spryker\Shared\SessionExtension\Dependency\Plugin\SessionHandlerProviderPluginInterface` instead.
     *
     * @return string
     */
    public function getSessionHandlerRedisDataSourceNameYves()
    {
        return $this->buildDataSourceName(
            $this->get(SessionConstants::YVES_SESSION_REDIS_PROTOCOL),
            $this->get(SessionConstants::YVES_SESSION_REDIS_HOST),
            $this->get(SessionConstants::YVES_SESSION_REDIS_PORT),
            $this->get(SessionConstants::YVES_SESSION_REDIS_DATABASE, static::DEFAULT_REDIS_DATABASE),
            $this->get(SessionConstants::YVES_SESSION_REDIS_PASSWORD, false)
        );
    }

    /**
     * @deprecated Use `Spryker\Shared\SessionExtension\Dependency\Plugin\SessionHandlerProviderPluginInterface` instead.
     *
     * @return array|string
     */
    public function getSessionHandlerRedisConnectionParametersYves()
    {
        $connectionConfiguration = $this->get(SessionConstants::YVES_SESSION_PREDIS_CLIENT_CONFIGURATION, []);

        if ($connectionConfiguration) {
            return $connectionConfiguration;
        }

        return $this->getSessionHandlerRedisDataSourceNameYves();
    }

    /**
     * @deprecated Use `Spryker\Shared\SessionExtension\Dependency\Plugin\SessionHandlerProviderPluginInterface` instead.
     *
     * @return array
     */
    public function getSessionHandlerRedisConnectionOptionsYves(): array
    {
        return $this->get(SessionConstants::YVES_SESSION_PREDIS_CLIENT_OPTIONS, []);
    }

    /**
     * @deprecated Use `Spryker\Shared\SessionExtension\Dependency\Plugin\SessionHandlerProviderPluginInterface` instead.
     *
     * @return string
     */
    public function getSessionHandlerFileSavePath()
    {
        return $this->get(SessionConstants::ZED_SESSION_FILE_PATH);
    }
}
