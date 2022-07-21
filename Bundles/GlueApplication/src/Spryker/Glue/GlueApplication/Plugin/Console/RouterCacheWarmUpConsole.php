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
class RouterCacheWarmUpConsole extends Console
{
    /**
     * @var string
     */
    protected const NAME = 'api:router:cache:warm-up';

    /**
     * @var string
     */
    protected const ARGUMENT_GLUE_APPLICATION_NAME = 'application_name';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setName(static::NAME)
            ->setDescription('Builds a fresh cache for the ApiApplication.')
            ->setDefinition([
                new InputArgument(static::ARGUMENT_GLUE_APPLICATION_NAME, InputArgument::OPTIONAL, 'A Glue Application name.'),
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
        /** @var string|null $argumentApplicationName */
        $argumentApplicationName = $this->input->getArgument(static::ARGUMENT_GLUE_APPLICATION_NAME);

        $this->getFactory()->createRouterCacheCollector()->warmUp($argumentApplicationName ? [$argumentApplicationName] : []);

        return static::CODE_SUCCESS;
    }
}
