<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Communication\Console;

use Spryker\Zed\Console\Business\Model\Console;
use Spryker\Zed\Maintenance\Business\MaintenanceFacade;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method MaintenanceFacade getFacade()
 */
class DependencyTreeValidatorConsole extends Console
{

    const COMMAND_NAME = 'code:dependency-tree-validate';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName(self::COMMAND_NAME)
            ->setHelp('<info>' . self::COMMAND_NAME . ' -h</info>')
            ->setDescription('Validate dependency tree');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @throws \Exception
     *
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->info('Validate dependency tree.');

        $dependencyViolations = $this->getFacade()->getDependencyViolations();

        $this->info(sprintf('Found "%d" wrong dependencies', count($dependencyViolations)));
        foreach ($dependencyViolations as $dependencyViolation) {
            $this->info($dependencyViolation);
        }
    }

}
