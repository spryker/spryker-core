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

    /**
     * Default Redis database number
     */
    const DEFAULT_REDIS_DATABASE = 0;

    /**
     * @return array
     */
    public function getSessionStorageOptions()
    {
        $sessionStorageOptions = [
            'name' => str_replace('.', '-', $this->get(SessionConstants::ZED_SESSION_COOKIE_NAME)),
            'cookie_lifetime' => $this->getSessionCookieTimeToLive(),
            'cookie_secure' => $this->secureCookie(),
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
     * @return string
     */
    public function getSessionHandlerRedisDataSourceNameZed()
    {
        $authFragment = '';
        if ($this->getConfig()->hasKey(SessionConstants::ZED_SESSION_REDIS_PASSWORD)) {
            $authFragment = sprintf('h:%s@', $this->get(SessionConstants::ZED_SESSION_REDIS_PASSWORD));
        }

        return sprintf(
            '%s://%s%s:%s?database=%s',
            $this->get(SessionConstants::ZED_SESSION_REDIS_PROTOCOL),
            $authFragment,
            $this->get(SessionConstants::ZED_SESSION_REDIS_HOST),
            $this->get(SessionConstants::ZED_SESSION_REDIS_PORT),
            $this->get(SessionConstants::ZED_SESSION_REDIS_DATABASE, 0)
        );
    }

    /**
     * @return string
     */
    public function getSessionHandlerRedisDataSourceNameYves()
    {
        $authFragment = '';
        if ($this->getConfig()->hasKey(SessionConstants::YVES_SESSION_REDIS_PASSWORD)) {
            $authFragment = sprintf('h:%s@', $this->get(SessionConstants::YVES_SESSION_REDIS_PASSWORD));
        }

        return sprintf(
            '%s://%s%s:%s?database=%s',
            $this->get(SessionConstants::YVES_SESSION_REDIS_PROTOCOL),
            $authFragment,
            $this->get(SessionConstants::YVES_SESSION_REDIS_HOST),
            $this->get(SessionConstants::YVES_SESSION_REDIS_PORT),
            $this->get(SessionConstants::YVES_SESSION_REDIS_DATABASE, 0)
        );
    }

    /**
     * @return string
     */
    public function getSessionHandlerFileSavePath()
    {
        return $this->get(SessionConstants::ZED_SESSION_FILE_PATH);
    }

}
