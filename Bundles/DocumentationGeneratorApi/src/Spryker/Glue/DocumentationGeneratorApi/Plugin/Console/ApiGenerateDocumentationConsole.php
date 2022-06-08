<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorApi\Plugin\Console;

use Spryker\Glue\Kernel\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Glue\DocumentationGeneratorApi\DocumentationGeneratorApiFactory getFactory()
 */
class ApiGenerateDocumentationConsole extends Console
{
    /**
     * @var string
     */
    protected const COMMAND_NAME = 'api:generate:documentation';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setName(static::COMMAND_NAME)
            ->addOption(
                'application',
                'a',
                4,
                'Application name',
            )
            ->setDescription('Generates documentation for API applications.');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $application = $input->getOption('application');
        $applicationOptionValue = $application && is_string($application) ? [$application] : [];

        $this->getFactory()->createDocumentationGenerator()->generateDocumentation($applicationOptionValue);

        return static::CODE_SUCCESS;
    }
}
