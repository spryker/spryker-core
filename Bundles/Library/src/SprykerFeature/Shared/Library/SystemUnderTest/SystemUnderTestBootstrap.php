<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\SystemUnderTest;

use Propel\Runtime\Propel;
// This is the only place where Project namespace is allowed
// @todo this has to be refactored with the new test concept
use Pyz\Yves\Application\Communication\YvesBootstrap;
use Pyz\Zed\Application\Communication\ZedBootstrap;
use ReflectionObject;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Yves\Kernel\Locator;
use SprykerFeature\Shared\Library\Application\Environment;
use SprykerFeature\Shared\Library\Application\TestEnvironment;

class SystemUnderTestBootstrap
{

    const APPLICATION_ZED = 'Zed';
    const APPLICATION_YVES = 'Yves';
    const APPLICATION_SHARED = 'Shared';
    const APPLICATION_CLIENT = 'Client';

    /**
     * @var SystemUnderTestBootstrap
     */
    protected static $instance;

    /**
     * @var array
     */
    protected $applications = [
        self::APPLICATION_ZED,
        self::APPLICATION_YVES,
        self::APPLICATION_SHARED,
        self::APPLICATION_CLIENT,
    ];

    /**s
     * @return SystemUnderTestBootstrap
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param string $application
     *
     * @throws \ErrorException
     * @throws \Exception
     */
    public function bootstrap($application = self::APPLICATION_ZED)
    {
        Propel::disableInstancePooling();

        $this->validateApplication($application);
        error_reporting(E_ALL | E_STRICT);
        ini_set('display_errors', 1);

        defined('IS_CLI') or define('IS_CLI', false);
        defined('APPLICATION') or define('APPLICATION', strtoupper($application));

        $path = realpath(__DIR__ . '/../../../../../../../../../..');
        defined('APPLICATION_ROOT_DIR') or define('APPLICATION_ROOT_DIR', $path);

        \Zend_Session::$_unitTestEnabled = true;
        TestEnvironment::forceSystemUnderTest();
        Environment::initialize($application);

        if (self::APPLICATION_ZED === $application) {
            $this->bootstrapZed();
        }
        if (self::APPLICATION_YVES === $application) {
            $this->bootstrapYves();
        }
    }

    /**
     * @param $application
     *
     * @throws \Exception
     */
    protected function validateApplication($application)
    {
        if (!in_array($application, $this->applications)) {
            throw new \Exception('Given application "' . $application . '" is not a valid application for running tests!');
        }
    }

    protected function bootstrapZed()
    {
        $application = new ZedBootstrap();
        $locator = \SprykerEngine\Zed\Kernel\Locator::getInstance();
        $this->resetLocator($locator);
        $application->boot();
    }

    /**
     * @TODO do we need to bootstrap Yves in a test case?
     */
    protected function bootstrapYves()
    {
        $application = new YvesBootstrap();

        $locator = Locator::getInstance();
        $this->resetLocator($locator);

        $application->boot();
    }

    /**
     * @param $locator
     */
    private function resetLocator(LocatorLocatorInterface $locator)
    {
        $refObject = new ReflectionObject($locator);
        $parent = $refObject->getParentClass();

        $refProperty = $parent->getProperty('instance');
        $refProperty->setAccessible(true);
        $refProperty->setValue(null);
    }

}
