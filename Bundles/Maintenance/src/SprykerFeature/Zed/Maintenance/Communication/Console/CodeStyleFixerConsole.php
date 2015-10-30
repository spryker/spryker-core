<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Communication\Console;

use SprykerFeature\Zed\Console\Business\Model\Console;
use SprykerFeature\Zed\Maintenance\Business\MaintenanceFacade;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method MaintenanceFacade getFacade()
 */
class CodeStyleFixerConsole extends Console
{

    const COMMAND_NAME = 'code:fix-style';

    const BUNDLE = 'bundle';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName(self::COMMAND_NAME)
            ->setHelp('<info>' . self::COMMAND_NAME . ' -h</info>')
            ->setDescription('Fix code style for a specific bundle')
        ;

        $this->addArgument(self::BUNDLE, InputArgument::OPTIONAL, 'Name of bundle to fix code style');
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
        $bundle = $this->input->getArgument(self::BUNDLE);
        if ($bundle) {
            $this->info('Fix code style in ' . $this->input->getArgument(self::BUNDLE) . ' bundle');
            $this->getFacade()->fixCodeStyle($bundle);
            return;
        }

        $this->info('Fix code style in all bundles');
        $this->getFacade()->fixCodeStyle();
    }

}
