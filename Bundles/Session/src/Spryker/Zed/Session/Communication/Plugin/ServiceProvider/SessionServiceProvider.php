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
            'cookie_lifetime' => Config::get(ApplicationConstants::ZED_STORAGE_SESSION_TIME_TO_LIVE),
            'name' => Config::get(ApplicationConstants::ZED_STORAGE_SESSION_COOKIE_NAME),
            'cookie_secure' => Config::get(ApplicationConstants::ZED_COOKIE_SECURE, true),
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

        $saveHandler = Config::get(ApplicationConstants::ZED_SESSION_SAVE_HANDLER);
        $savePath = $this->getSavePath($saveHandler);

        $sessionHelper = new SessionFactory();

        switch ($saveHandler) {
            case SessionConstants::SESSION_HANDLER_COUCHBASE:
                $savePath = isset($savePath) && !empty($savePath) ? $savePath : null;

                $sessionHelper->registerCouchbaseSessionHandler($savePath);
                break;

            case SessionConstants::SESSION_HANDLER_MYSQL:
                $savePath = isset($savePath) && !empty($savePath) ? $savePath : null;
                $sessionHelper->registerMysqlSessionHandler($savePath);
                break;

            case SessionConstants::SESSION_HANDLER_REDIS:
                $savePath = isset($savePath) && !empty($savePath) ? $savePath : null;
                $sessionHelper->registerRedisSessionHandler($savePath);
                break;

            case SessionConstants::SESSION_HANDLER_FILE:
                $savePath = isset($savePath) && !empty($savePath) ? $savePath : null;
                $sessionHelper->registerFileSessionHandler($savePath);
                break;

            default:
                if (isset($saveHandler) && !empty($saveHandler)) {
                    ini_set('session.save_handler', $saveHandler);
                }
                if (isset($savePath) && !empty($savePath)) {
                    session_save_path($savePath);
                }
        }

        ini_set('session.auto_start', false);
    }

    /**
     * @param string $saveHandler
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function getSavePath($saveHandler)
    {
        $path = null;

        if (SessionConstants::SESSION_HANDLER_REDIS === $saveHandler) {
            $path = Config::get(ApplicationConstants::ZED_STORAGE_SESSION_REDIS_PROTOCOL)
                . '://' . Config::get(ApplicationConstants::ZED_STORAGE_SESSION_REDIS_HOST)
                . ':' . Config::get(ApplicationConstants::ZED_STORAGE_SESSION_REDIS_PORT);
        }

        if (SessionConstants::SESSION_HANDLER_FILE === $saveHandler) {
            $path = Config::get(ApplicationConstants::ZED_STORAGE_SESSION_FILE_PATH);
        }

        return $path;
    }

}
