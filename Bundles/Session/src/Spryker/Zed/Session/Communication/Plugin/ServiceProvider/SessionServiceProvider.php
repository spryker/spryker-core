<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Session\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Client\Session\SessionClientInterface;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Session\SessionConstants;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Session\Business\Model\SessionFactory;
use Spryker\Zed\Storage\StorageConfig;

/**
 * @method \Spryker\Zed\Session\Communication\SessionCommunicationFactory getFactory()
 */
class SessionServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{

    /**
     * @var \Spryker\Client\Session\SessionClientInterface
     */
    private $client;

    /**
     * @param \Spryker\Client\Session\SessionClientInterface $client
     *
     * @return void
     */
    public function setClient(SessionClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['session.test'] = Config::get(SessionConstants::SESSION_IS_TEST, false);

        $app['session.storage.options'] = [
            'name' => str_replace('.', '-', Config::get(SessionConstants::ZED_SESSION_COOKIE_NAME)),
            'cookie_lifetime' => Config::get(SessionConstants::ZED_SESSION_TIME_TO_LIVE),
            'cookie_secure' => $this->secureCookie(),
            'cookie_httponly' => true,
            'use_only_cookies' => true,
        ];

        $this->client->setContainer($app['session']);
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
        if (PHP_SAPI === 'cli') {
            return;
        }

        $saveHandler = Config::get(SessionConstants::ZED_SESSION_SAVE_HANDLER);
        $savePath = $this->getSavePath($saveHandler);

        $sessionHelper = new SessionFactory();

        switch ($saveHandler) {
            case SessionConstants::SESSION_HANDLER_COUCHBASE:
                $savePath = !empty($savePath) ? $savePath : null;

                $sessionHelper->registerCouchbaseSessionHandler($savePath);
                break;

            case SessionConstants::SESSION_HANDLER_MYSQL:
                $savePath = !empty($savePath) ? $savePath : null;
                $sessionHelper->registerMysqlSessionHandler($savePath);
                break;

            case SessionConstants::SESSION_HANDLER_REDIS:
                $savePath = !empty($savePath) ? $savePath : null;
                $sessionHelper->registerRedisSessionHandler($savePath);
                break;

            case SessionConstants::SESSION_HANDLER_FILE:
                $savePath = !empty($savePath) ? $savePath : null;
                $sessionHelper->registerFileSessionHandler($savePath);
                break;

            default:
                if (isset($saveHandler) && !empty($saveHandler)) {
                    ini_set('session.save_handler', $saveHandler);
                }
                if (!empty($savePath)) {
                    session_save_path($savePath);
                }
        }

        ini_set('session.auto_start', false);
    }

    /**
     * @param string $saveHandler
     *
     * @return string|null
     */
    protected function getSavePath($saveHandler)
    {
        $path = null;

        if (SessionConstants::SESSION_HANDLER_REDIS === $saveHandler) {
            $path = sprintf(
                '%s://%s:%s?database=%s',
                Config::get(SessionConstants::ZED_SESSION_REDIS_PROTOCOL),
                Config::get(SessionConstants::ZED_SESSION_REDIS_HOST),
                Config::get(SessionConstants::ZED_SESSION_REDIS_PORT),
                Config::get(SessionConstants::ZED_SESSION_REDIS_DATABASE, StorageConfig::DEFAULT_REDIS_DATABASE)
            );

            if (Config::hasKey(SessionConstants::ZED_SESSION_REDIS_PASSWORD)) {
                $path = sprintf(
                    '%s://h:%s@%s:%s?database=%s',
                    Config::get(SessionConstants::ZED_SESSION_REDIS_PROTOCOL),
                    Config::get(SessionConstants::ZED_SESSION_REDIS_PASSWORD),
                    Config::get(SessionConstants::ZED_SESSION_REDIS_HOST),
                    Config::get(SessionConstants::ZED_SESSION_REDIS_PORT),
                    Config::get(SessionConstants::ZED_SESSION_REDIS_DATABASE, StorageConfig::DEFAULT_REDIS_DATABASE)
                );
            }

            return $path;
        }

        if (SessionConstants::SESSION_HANDLER_FILE === $saveHandler) {
            $path = Config::get(SessionConstants::ZED_SESSION_FILE_PATH);
        }

        return $path;
    }

    /**
     * Secure flag of cookies can only be set to true if SSL is enabled. If you set it to true
     * without SSL enabled you will not get the same session in browsers like Firefox and Safari
     *
     * @return bool
     */
    protected function secureCookie()
    {
        if (Config::get(ApplicationConstants::ZED_SSL_ENABLED, false)
            && Config::get(SessionConstants::ZED_SESSION_COOKIE_SECURE, true)
        ) {
            return true;
        }

        return false;
    }

}
