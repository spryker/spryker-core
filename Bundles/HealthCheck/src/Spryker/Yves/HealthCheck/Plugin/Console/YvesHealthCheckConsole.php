<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\HealthCheck\Plugin\Console;

use Generated\Shared\Transfer\HealthCheckRequestTransfer;
use Spryker\Yves\Kernel\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Yves\HealthCheck\HealthCheckFactory getFactory()
 */
class YvesHealthCheckConsole extends Console
{
    protected const COMMAND_NAME = 'health:check:yves';
    protected const DESCRIPTION = 'Checks that Yves related services are available';

    protected const SERVICES_OPTION = 'services';
    protected const SERVICES_OPTION_SHORTCUT = 's';
    protected const SERVICES_OPTION_DESCRIPTION = 'Services to include.';

    protected const OUTPUT_FORMAT_OPTION = 'format';
    protected const OUTPUT_FORMAT_OPTION_SHORTCUT = 'f';
    protected const OUTPUT_FORMAT_OPTION_DESCRIPTION = 'Output format.';
    protected const OUTPUT_FORMAT_OPTION_DEFAULT = 'console';

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
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            static::SERVICES_OPTION_DESCRIPTION,
            []
        );

        $this->addOption(
            static::OUTPUT_FORMAT_OPTION,
            static::OUTPUT_FORMAT_OPTION_SHORTCUT,
            InputOption::VALUE_OPTIONAL,
            static::OUTPUT_FORMAT_OPTION_DESCRIPTION,
            static::OUTPUT_FORMAT_OPTION_DEFAULT
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
        $healthCheckRequestTransfer = (new HealthCheckRequestTransfer())
            ->setServices(implode(',', $input->getOption(static::SERVICES_OPTION)))
            ->setFormat($input->getOption(static::OUTPUT_FORMAT_OPTION));

        $healthCheckResponseTransfer = $this->getFactory()
            ->getHealthCheckService()
            ->checkYvesHealthCheck($healthCheckRequestTransfer);

        $output->writeln($healthCheckResponseTransfer->getMessage());

        return static::CODE_SUCCESS;
    }
}
