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
class DependencyTreeBuilderConsole extends Console
{

    const COMMAND_NAME = 'code:dependency-tree';

    const OPTION_APPLICATION = 'application';
    const OPTION_BUNDLE = 'bundle';
    const OPTION_LAYER = 'layer';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName(self::COMMAND_NAME)
            ->setHelp('<info>' . self::COMMAND_NAME . ' -h</info>')
            ->setDescription('Build dependency tree');

        $this->addOption(self::OPTION_APPLICATION, 'a', InputOption::VALUE_OPTIONAL, 'Name of application to build the dependency tree');
        $this->addOption(self::OPTION_BUNDLE, 'b', InputOption::VALUE_OPTIONAL, 'Name of bundle to build the dependency tree');
        $this->addOption(self::OPTION_LAYER, 'l', InputOption::VALUE_OPTIONAL, 'Name of layer to build the dependency tree (only for Zed)');
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
        $application = '*';
        $bundle = '*';
        $layer = '*';

        if ($this->input->getOption(self::OPTION_APPLICATION)) {
            $application = $this->input->getOption(self::OPTION_APPLICATION);
        }
        if ($this->input->getOption(self::OPTION_BUNDLE)) {
            $bundle = $this->input->getOption(self::OPTION_BUNDLE);
        }
        if ($this->input->getOption(self::OPTION_LAYER)) {
            $layer = $this->input->getOption(self::OPTION_LAYER);
        }

        $this->info(sprintf(
            'Build dependency tree.',
            $application, $bundle, $layer
        ));

        $this->getFacade()->buildDependencyTree($application, $bundle, $layer);
    }

}
