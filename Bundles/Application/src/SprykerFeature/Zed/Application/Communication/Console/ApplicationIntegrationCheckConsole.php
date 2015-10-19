<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Communication\Console;

use SprykerEngine\Shared\Config;
use SprykerFeature\Shared\Application\ApplicationConfig;
use SprykerFeature\Shared\Library\Application\Environment;
use SprykerFeature\Zed\Application\Business\ApplicationFacade;
use SprykerFeature\Zed\Application\Communication\Console\ApplicationCheckStep\AbstractApplicationCheckStep;
use SprykerFeature\Zed\Console\Business\Model\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method ApplicationFacade getFacade()
 */
class ApplicationIntegrationCheckConsole extends Console
{

    const COMMAND_NAME = 'application:integration-check';
    const DESCRIPTION = 'Execute steps to check application';

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::DESCRIPTION);

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @throws \Exception
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (Config::get(ApplicationConfig::ALLOW_INTEGRATION_CHECKS, false)) {
            throw new \Exception('This command is only allowed to run in development environment');
        }

        $this->checkApplication(
            $this->getCheckSteps()
        );
    }

    /**
     * @param AbstractApplicationCheckStep[] $steps
     */
    private function checkApplication(array $steps)
    {
        $consoleLogger = new ConsoleLogger($this->output);

        foreach ($steps as $step) {
            $step->setLogger($consoleLogger);
            $step->run();
        }
    }

    /**
     * @return AbstractApplicationCheckStep[]
     */
    protected function getCheckSteps()
    {
        $steps = $this->getFacade()->getCheckSteps();

        return $steps;
    }

}
