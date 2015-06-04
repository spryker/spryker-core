<?php
namespace SprykerFeature\Zed\Application\Communication\Plugin\ServiceProvider;

use SprykerFeature\Shared\Library\Application\TestEnvironment;
use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\Library\Session;
use SprykerFeature\Shared\System\SystemConfig;
use SprykerFeature\Zed\Application\Business\Model\Twig\EnvironmentInfo;
use Silex\Application;
use Silex\ServiceProviderInterface;

class SessionServiceProvider implements ServiceProviderInterface
{

    const SESSION_HANDLER_REDIS = 'redis';
    const SESSION_HANDLER_MYSQL = 'mysql';
    const SESSION_HANDLER_COUCHBASE = 'couchbase';
    const SESSION_SAVE_HANDLER = 'session.save_handler';
    const SESSION_AUTO_START = 'session.auto_start';

    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
    }

    /**
     * @param Application $app
     * @throws \Exception
     */
    public function boot(Application $app)
    {
        if (PHP_SAPI === 'cli') {
            return;
        }
        if (TestEnvironment::isSystemUnderTest()) {
            // TODO remove this
            \Zend_Session::$_unitTestEnabled = true;
            return;
        }

        $saveHandler = Config::get(SystemConfig::ZED_SESSION_SAVE_HANDLER);
        $savePath = $this->getSavePath($saveHandler);

        switch ($saveHandler) {
            case self::SESSION_HANDLER_COUCHBASE:
                $savePath = isset($savePath) && !empty($savePath) ? $savePath : null;
                $sessionHelper = new Session();
                $sessionHelper->registerCouchbaseSessionHandler($savePath);
                break;

            case self::SESSION_HANDLER_MYSQL:
                $savePath = isset($savePath) && !empty($savePath) ? $savePath : null;
                $sessionHelper = new Session();
                $sessionHelper->registerMysqlSessionHandler($savePath);
                break;

            case self::SESSION_HANDLER_REDIS:
                $savePath = isset($savePath) && !empty($savePath) ? $savePath : null;
                $sessionHelper = new Session();
                $sessionHelper->registerRedisSessionHandler($savePath);
                break;

            default:
                if (isset($saveHandler) && !empty($saveHandler)) {
                    ini_set(self::SESSION_SAVE_HANDLER, $saveHandler);
                }
                if (isset($savePath) && !empty($savePath)) {
                    session_save_path($savePath);
                }
        }

        ini_set(self::SESSION_AUTO_START, false);
    }

    /**
     * @param string $saveHandler
     * @return string
     *
     * @throws \Exception
     */
    protected function getSavePath($saveHandler)
    {
        $path = null;
        switch($saveHandler){
            case self::SESSION_HANDLER_REDIS:
                $path = Config::get(SystemConfig::ZED_STORAGE_SESSION_REDIS_PROTOCOL)
                    . '://' . Config::get(SystemConfig::ZED_STORAGE_SESSION_REDIS_HOST)
                    . ':' . Config::get(SystemConfig::ZED_STORAGE_SESSION_REDIS_PORT);
                break;
            default: throw new \Exception('Needs implementation for mysql and couchbase!');
        }
        return $path;
    }

}
