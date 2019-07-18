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
 */
class MaintenanceDisableConsole extends AbstractMaintenanceConsole
{
    public const COMMAND_NAME = 'maintenance:disable';
    public const COMMAND_DESCRIPTION = 'Will disable the maintenance mode while setup/deploy.';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->addArgument(static::ARGUMENT_APPLICATION, InputArgument::OPTIONAL, 'Name of the application for which the maintenance mode should be disabled. (zed|yves)', static::APPLICATION_ALL);
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

        $this->disableYvesMaintenancePage($application);
        $this->disableZedMaintenancePage($application);

        return static::CODE_SUCCESS;
    }

    /**
     * @param string $application
     *
     * @return void
     */
    protected function disableYvesMaintenancePage($application)
    {
        if ($application === static::APPLICATION_ALL || $application === static::APPLICATION_YVES) {
            $this->getFacade()->disableMaintenanceForYves();
        }
    }

    /**
     * @param string $application
     *
     * @return void
     */
    protected function disableZedMaintenancePage($application)
    {
        if ($application === static::APPLICATION_ALL || $application === static::APPLICATION_ZED) {
            $this->getFacade()->disableMaintenanceForZed();
        }
    }
}
