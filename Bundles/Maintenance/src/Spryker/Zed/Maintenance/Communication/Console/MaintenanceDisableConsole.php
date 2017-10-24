<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Maintenance\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Maintenance\Business\MaintenanceFacade getFacade()
 */
class MaintenanceDisableConsole extends Console
{
    const COMMAND_NAME = 'maintenance:disable';
    const COMMAND_DESCRIPTION = 'Will disable the maintenance mode while setup/deploy.';
    const ARGUMENT_APPLICATION = 'application';

    const APPLICATION_ALL = 'all';
    const APPLICATION_YVES = 'yves';
    const APPLICATION_ZED = 'zed';

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
        $application = strtolower($input->getArgument(static::ARGUMENT_APPLICATION));

        if ($application === static::APPLICATION_ALL || $application === static::APPLICATION_YVES) {
            $fileName = APPLICATION_ROOT_DIR . '/public/Yves/maintenance.marker';
            if (file_exists($fileName)) {
                unlink($fileName);
            }
        }

        if ($application === static::APPLICATION_ALL || $application === static::APPLICATION_ZED) {
            $fileName = APPLICATION_ROOT_DIR . '/public/Zed/maintenance.marker';
            if (file_exists($fileName)) {
                unlink($fileName);
            }
        }

        return static::CODE_SUCCESS;
    }
}
