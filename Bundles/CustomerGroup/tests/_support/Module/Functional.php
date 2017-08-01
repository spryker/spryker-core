<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace CustomerGroup\Module;

use Codeception\Module;
use Codeception\TestInterface;
use Propel\Runtime\Propel;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\TwigServiceProvider as SilexTwigServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Spryker\Service\UtilDateTime\ServiceProvider\DateTimeFormatterServiceProvider;
use Spryker\Shared\Kernel\Communication\Application;
use Spryker\Zed\Assertion\Communication\Plugin\ServiceProvider\AssertionServiceProvider;
use Spryker\Zed\Country\Business\CountryFacade;
use Spryker\Zed\Kernel\Communication\Plugin\Pimple;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\Propel\Communication\Plugin\ServiceProvider\PropelServiceProvider;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

class Functional extends Module
{

    /**
     * @return void
     */
    public function _initialize()
    {
        $this->registerServiceProvider();
        $this->runInstaller();
    }

    /**
     * @return void
     */
    private function registerServiceProvider()
    {
        $application = new Application();
        $application->register(new AssertionServiceProvider());
        $application->register(new SilexTwigServiceProvider());
        $application->register(new ValidatorServiceProvider());
        $application->register(new FormServiceProvider());
        $application->register(new DateTimeFormatterServiceProvider());

        $propelServiceProvider = new PropelServiceProvider();
        $propelServiceProvider->boot($application);

        $pimple = new Pimple();
        $pimple->setApplication($application);
    }

    /**
     * @return void
     */
    private function runInstaller()
    {
        $messenger = $this->getMessenger();

        $localeFacade = new LocaleFacade();
        $localeFacade->install($messenger);

        $countryFacade = new CountryFacade();
        $countryFacade->install($messenger);
    }

    /**
     * @return \Symfony\Component\Console\Logger\ConsoleLogger
     */
    protected function getMessenger()
    {
        $messenger = new ConsoleLogger(
            new ConsoleOutput(OutputInterface::VERBOSITY_QUIET)
        );

        return $messenger;
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test)
    {
        parent::_before($test);

        Propel::getWriteConnection('zed')->beginTransaction();
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test)
    {
        parent::_after($test);

        Propel::getWriteConnection('zed')->rollBack();

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

    /**
     * @param \Codeception\TestInterface $test
     * @param bool $fail
     *
     * @return void
     */
    public function _failed(TestInterface $test, $fail)
    {
        parent::_failed($test, $fail);

        Propel::getWriteConnection('zed')->rollBack();

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

}
