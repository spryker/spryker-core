<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorApi\Plugin\Console;

use Generated\Shared\Transfer\DocumentationInvalidationVoterRequestTransfer;
use Spryker\Glue\Kernel\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Glue\DocumentationGeneratorApi\DocumentationGeneratorApiFactory getFactory()
 */
class ApiGenerateDocumentationConsole extends Console
{
    /**
     * @var string
     */
    protected const OPTION_INVALIDATED_AFTER_INTERVAL = 'invalidated-after-interval';

    /**
     * @var string
     */
    protected const SKIP_MESSAGE = 'Dynamic entity configuration is not invalidated. Skip generating documentation.';

    /**
     * @var string
     */
    protected const OPTION_INVALIDATED_AFTER_INTERVAL_DESCRIPTION = 'The interval verifies if the dynamic entity configuration has been invalidated. Example: 1day, 1hour, 1minute, 1second.';


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
            ->addOption(
                static::OPTION_INVALIDATED_AFTER_INTERVAL,
                null,
                InputOption::VALUE_OPTIONAL,
                static::OPTION_INVALIDATED_AFTER_INTERVAL_DESCRIPTION,
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
        $invalidateAfterInterval = $input->getOption(static::OPTION_INVALIDATED_AFTER_INTERVAL);

        if ($invalidateAfterInterval && is_string($invalidateAfterInterval)) {
            $documentationInvalidationVoterRequestTransfer = (new DocumentationInvalidationVoterRequestTransfer())->setInterval($invalidateAfterInterval);

            $isInvalidated = $this->getFactory()->createInvalidationVerifier()->isInvalidated($documentationInvalidationVoterRequestTransfer);

            if (!$isInvalidated) {
                $output->writeln(static::SKIP_MESSAGE);

                return static::CODE_SUCCESS;
            }
        }

        $this->getFactory()->createDocumentationGenerator()->generateDocumentation($applicationOptionValue);

        return static::CODE_SUCCESS;
    }
}
