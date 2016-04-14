<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Customer\Module;

use Codeception\TestCase;
use Codeception\Module;
use Propel\Runtime\Propel;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\TwigServiceProvider as SilexTwigServiceProvider;
use Spryker\Shared\Application\Communication\Application;
use Spryker\Zed\Application\Communication\Plugin\Pimple;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\DateFormatterServiceProvider;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\TwigServiceProvider;
use Spryker\Zed\Assertion\Communication\Plugin\ServiceProvider\AssertionServiceProvider;
use Spryker\Zed\Console\Business\Model\ConsoleMessenger;
use Spryker\Zed\Country\Communication\Plugin\Installer as CountryInstallerPlugin;
use Spryker\Zed\Locale\Communication\Plugin\Installer as LocaleInstallerPlugin;
use Spryker\Zed\Messenger\Business\MessengerFacade;
use Spryker\Zed\Propel\Communication\Plugin\ServiceProvider\PropelServiceProvider;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

class Functional extends Module
{

    /**
     * @param array $config
     */
    public function __construct($config = null)
    {
        parent::__construct($config);

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
        $application->register(new TwigServiceProvider());
        $application->register(new DateFormatterServiceProvider());

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
        foreach ($this->getInstallerCollection() as $installer) {
            $installer->setMessenger($this->getMessenger());
            $installer->run();
        }
    }

    /**
     * @return \Spryker\Zed\Console\Business\Model\ConsoleMessenger
     */
    protected function getMessenger()
    {
        $messenger = new ConsoleMessenger(
            new ConsoleOutput(OutputInterface::VERBOSITY_QUIET)
        );

        return $messenger;
    }

    /**
     * @param TestCase $test
     */
    public function _before(TestCase $test)
    {
        parent::_before($test);

        Propel::getWriteConnection('zed')->beginTransaction();
    }

    /**
     * @param TestCase $test
     */
    public function _after(TestCase $test)
    {
        parent::_after($test);

        Propel::getWriteConnection('zed')->rollBack();

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

    /**
     * @param TestCase $test
     * @apram $fail
     */
    public function _failed(TestCase $test, $fail)
    {
        parent::_failed($test, $fail);

        Propel::getWriteConnection('zed')->rollBack();

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

    /**
     * @return array
     */
    private function getInstallerCollection()
    {
        return $installerCollection = [
            new LocaleInstallerPlugin(),
            new CountryInstallerPlugin(),
        ];
    }

}
