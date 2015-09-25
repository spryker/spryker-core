<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Session\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Client\Session\Service\SessionClientInterface;
use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\Session\SessionConfig;
use SprykerFeature\Shared\System\SystemConfig;
use SprykerFeature\Zed\Session\Business\Model\SessionHelper;

class SessionServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{

    /**
     * @var SessionClientInterface
     */
    private $client;

    /**
     * @param SessionClientInterface $client
     */
    public function setClient(SessionClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
        $this->client->setContainer($app['session']);
    }

    /**
     * @param Application $app
     */
    public function boot(Application $app)
    {
        if (PHP_SAPI === 'cli') {
            return;
        }

        $saveHandler = Config::get(SystemConfig::ZED_SESSION_SAVE_HANDLER);
        $savePath = $this->getSavePath($saveHandler);

        $sessionHelper = new SessionHelper();

        switch ($saveHandler) {
            case SessionConfig::SESSION_HANDLER_COUCHBASE:
                $savePath = isset($savePath) && !empty($savePath) ? $savePath : null;

                $sessionHelper->registerCouchbaseSessionHandler($savePath);
                break;

            case SessionConfig::SESSION_HANDLER_MYSQL:
                $savePath = isset($savePath) && !empty($savePath) ? $savePath : null;
                $sessionHelper->registerMysqlSessionHandler($savePath);
                break;

            case SessionConfig::SESSION_HANDLER_REDIS:
                $savePath = isset($savePath) && !empty($savePath) ? $savePath : null;
                $sessionHelper->registerRedisSessionHandler($savePath);
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
        switch ($saveHandler) {
            case SessionConfig::SESSION_HANDLER_REDIS:
                $path = Config::get(SystemConfig::ZED_STORAGE_SESSION_REDIS_PROTOCOL)
                    . '://' . Config::get(SystemConfig::ZED_STORAGE_SESSION_REDIS_HOST)
                    . ':' . Config::get(SystemConfig::ZED_STORAGE_SESSION_REDIS_PORT);
                break;
            case SessionConfig::SESSION_HANDLER_FILE:
                $path = Config::get(SystemConfig::YVES_STORAGE_SESSION_REDIS_PROTOCOL);
                break;
            default:
                throw new \Exception('Needs implementation for mysql and couchbase!');
        }

        return $path;
    }

}
