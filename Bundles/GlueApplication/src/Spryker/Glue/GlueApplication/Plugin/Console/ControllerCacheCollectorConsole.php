<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Plugin\Console;

use Spryker\Glue\Kernel\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Glue\GlueApplication\GlueApplicationFactory getFactory()
 */
class ControllerCacheCollectorConsole extends Console
{
    /**
     * @var string
     */
    protected const NAME = 'glue-api:controller:cache:warm-up';

    /**
     * @var string
     */
    protected const ARGUMENT_APPLICATION_NAME = 'application';

    /**
     * @var string
     */
    protected const COMMAND_DESCRIPTION = 'Builds a fresh cache for the API applications controllers.';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setName(static::NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->setDefinition([
                new InputArgument(static::ARGUMENT_APPLICATION_NAME, InputArgument::OPTIONAL, 'A Glue Application name.'),
            ]);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string|null $applicationNameArgument */
        $applicationNameArgument = $this->input->getArgument(static::ARGUMENT_APPLICATION_NAME);

        $this->getFactory()->createControllerCacheWriter()->cache($applicationNameArgument);

        return static::CODE_SUCCESS;
    }
}
