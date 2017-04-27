<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Session;

use Spryker\Shared\Session\SessionConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;

class SessionConfig extends AbstractBundleConfig
{

    /**
     * @return array
     */
    public function getSessionStorageOptions()
    {
        $sessionStorageOptions = [
            'name' => str_replace('.', '-', $this->get(SessionConstants::YVES_SESSION_COOKIE_NAME)),
            'cookie_lifetime' => $this->getSessionCookieTimeToLive(),
            'cookie_secure' => $this->secureCookie(),
            'cookie_httponly' => true,
        ];

        $cookieDomain = $this->get(SessionConstants::YVES_SESSION_COOKIE_DOMAIN);
        if ($cookieDomain) {
            $sessionStorageOptions['cookie_domain'] = $cookieDomain;
        }

        return $sessionStorageOptions;
    }

    /**
     * Projects should use `SessionConstants::YVES_SESSION_COOKIE_TIME_TO_LIVE`. If they don't have it in
     * their config we will use the existing `SessionConstants::YVES_SESSION_TIME_TO_LIVE` as default value.
     *
     * @return int
     */
    private function getSessionCookieTimeToLive()
    {
        return (int)$this->get(SessionConstants::YVES_SESSION_COOKIE_TIME_TO_LIVE, $this->get(SessionConstants::YVES_SESSION_TIME_TO_LIVE));
    }

    /**
     * @return bool
     */
    protected function secureCookie()
    {
        return ($this->get(SessionConstants::YVES_SESSION_COOKIE_SECURE, true) && $this->get(SessionConstants::YVES_SSL_ENABLED, true));
    }

    /**
     * @return string
     */
    public function getConfiguredSessionHandlerName()
    {
        return $this->get(SessionConstants::YVES_SESSION_SAVE_HANDLER);
    }

    /**
     * @return int
     */
    public function getSessionLifeTime()
    {
        return (int)$this->get(SessionConstants::YVES_SESSION_TIME_TO_LIVE);
    }

    /**
     * @return string
     */
    public function getSessionHandlerRedisDataSourceName()
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
        return $this->get(SessionConstants::YVES_SESSION_FILE_PATH);
    }

}
