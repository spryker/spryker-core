<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\HealthCheck\src\Spryker\Zed\HealthCheck\Console;

use Generated\Shared\Transfer\HealthCheckRequestTransfer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Spryker\Zed\Kernel\Communication\Console\Console;

/**
 * @method \Spryker\Zed\HealthCheck\Communication\HealthCheckCommunicationFactory getFactory()
 */
class ZedHealthCheckConsole extends Console
{
    public const COMMAND_NAME = 'health:check:zed';
    public const DESCRIPTION = 'Checks that Zed related services are available';

    protected const SERVICES_OPTION = 'services';
    protected const SERVICES_OPTION_SHORTCUT = 's';
    protected const SERVICES_OPTION_DESCRIPTION = 'Services to include.';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription(static::DESCRIPTION);

        $this->addOption(
            static::SERVICES_OPTION,
            static::SERVICES_OPTION_SHORTCUT,
            InputOption::VALUE_OPTIONAL,
            static::SERVICES_OPTION_DESCRIPTION,
            []
        );

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $services = $input->getOption(static::SERVICES_OPTION);

        $healthCheckRequestTransfer = (new HealthCheckRequestTransfer())
            ->setServices($services);

        $healthCheckResponseTransfer = $this->getFactory()
            ->getHealthCheckService()
            ->checkZedHealthCheck($healthCheckRequestTransfer);

        return static::CODE_SUCCESS;
    }
}
