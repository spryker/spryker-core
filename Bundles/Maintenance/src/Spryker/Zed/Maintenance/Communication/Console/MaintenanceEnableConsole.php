<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Maintenance\Communication\Console;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Maintenance\Business\MaintenanceFacadeInterface getFacade()
 * @method \Spryker\Zed\Maintenance\Communication\MaintenanceCommunicationFactory getFactory()
 */
class MaintenanceEnableConsole extends AbstractMaintenanceConsole
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'maintenance:enable';

    /**
     * @var string
     */
    public const COMMAND_DESCRIPTION = 'Will enable the maintenance mode while setup/deploy.';

    /**
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();
        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->addArgument(static::ARGUMENT_APPLICATION, InputArgument::OPTIONAL, 'Name of the application for which the maintenance mode should be enabled. (zed|yves)', static::APPLICATION_ALL);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $application = $this->getApplicationName($input);

        $this->enableYvesMaintenancePage($application);
        $this->enableZedMaintenancePage($application);

        return static::CODE_SUCCESS;
    }

    /**
     * @param string $application
     *
     * @return void
     */
    protected function enableYvesMaintenancePage($application)
    {
        if ($application === static::APPLICATION_ALL || $application === static::APPLICATION_YVES) {
            $this->getFacade()->enableMaintenanceForYves();
        }
    }

    /**
     * @param string $application
     *
     * @return void
     */
    protected function enableZedMaintenancePage($application)
    {
        if ($application === static::APPLICATION_ALL || $application === static::APPLICATION_ZED) {
            $this->getFacade()->enableMaintenanceForZed();
        }
    }
}
