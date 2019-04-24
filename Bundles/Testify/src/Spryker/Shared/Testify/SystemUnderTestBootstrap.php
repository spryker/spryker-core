<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Testify;

use Exception;
use InvalidArgumentException;
use Propel\Runtime\Propel;
use ReflectionObject;
use Silex\Application;
use Spryker\Shared\Config\Application\Environment;
use Spryker\Shared\Config\Config;
use Spryker\Shared\ErrorHandler\ErrorHandlerEnvironment;
use Spryker\Shared\Kernel\LocatorLocatorInterface;
use Spryker\Yves\Kernel\Locator;
use Spryker\Zed\Kernel\Locator as KernelLocator;
use Spryker\Zed\Propel\Communication\Plugin\ServiceProvider\PropelServiceProvider;

/**
 * @deprecated Please switch to `Spryker\Zed\Testify\Bootstrap\ZedBootstrap`.
 */
class SystemUnderTestBootstrap
{
    public const APPLICATION_ZED = 'Zed';
    public const APPLICATION_YVES = 'Yves';
    public const APPLICATION_SHARED = 'Shared';
    public const APPLICATION_CLIENT = 'Client';
    public const TEST_ENVIRONMENT = 'devtest';

    /**
     * @var \Spryker\Shared\Testify\SystemUnderTestBootstrap|null
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

    /**
     * @return self
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
     * @return \Spryker\Shared\Application\Application
     */
    public function bootstrap($application = self::APPLICATION_ZED)
    {
        Propel::disableInstancePooling();

        $this->validateApplication($application);
        error_reporting(E_ALL | E_STRICT);
        ini_set('display_errors', 1);

        putenv("SESSION_IS_TEST=true");

        defined('APPLICATION') || define('APPLICATION', strtoupper($application));
        defined('APPLICATION_ENV') || define('APPLICATION_ENV', self::TEST_ENVIRONMENT);

        $path = realpath(__DIR__ . '/../../../../../../../../..');

        defined('APPLICATION_ROOT_DIR') || define('APPLICATION_ROOT_DIR', $path);

        Environment::initialize();

        $errorHandlerEnvironment = new ErrorHandlerEnvironment();
        $errorHandlerEnvironment->initialize();

        if ($application === self::APPLICATION_ZED) {
            return $this->bootstrapZed();
        }
        if ($application === self::APPLICATION_YVES) {
            return $this->bootstrapYves();
        }
    }

    /**
     * @param string $application
     *
     * @throws \Exception
     *
     * @return void
     */
    protected function validateApplication($application)
    {
        if (!in_array($application, $this->applications)) {
            throw new Exception('Given application "' . $application . '" is not a valid application for running tests!');
        }
    }

    /**
     * @return \Spryker\Shared\Application\Application
     */
    protected function bootstrapZed()
    {
        /** @var \Spryker\Zed\Application\Communication\ZedBootstrap $application */
        $application = $this->getBootstrapClass(TestifyConstants::BOOTSTRAP_CLASS_ZED);
        $locator = KernelLocator::getInstance();
        $this->resetLocator($locator);
        $kernel = $application->boot();

        $propelServiceProvider = new PropelServiceProvider();
        $propelServiceProvider->boot(new Application());

        return $kernel;
    }

    /**
     * @return \Spryker\Shared\Application\Application
     */
    protected function bootstrapYves()
    {
        /** @var \Pyz\Yves\ShopApplication\YvesBootstrap $application */
        $application = $this->getBootstrapClass(TestifyConstants::BOOTSTRAP_CLASS_YVES);

        $locator = Locator::getInstance();
        $this->resetLocator($locator);

        $kernel = $application->boot();

        return $kernel;
    }

    /**
     * @param string $configKey
     *
     * @throws \InvalidArgumentException
     *
     * @return object
     */
    private function getBootstrapClass($configKey)
    {
        if (!Config::hasKey($configKey)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Could not find a configured bootstrap class for config key "%s". You need to add the class name of your bootstrap class in your test configuration.',
                    $configKey
                )
            );
        }
        $bootstrapClassName = Config::get($configKey);

        return new $bootstrapClassName();
    }

    /**
     * @param \Spryker\Shared\Kernel\LocatorLocatorInterface $locator
     *
     * @return void
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
