<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\DocumentationGeneratorRestApi\Business\DocumentationGeneratorRestApiFacadeInterface getFacade()
 * @method \Spryker\Zed\DocumentationGeneratorRestApi\Communication\DocumentationGeneratorRestApiCommunicationFactory getFactory()
 */
class GenerateRestApiDocumentationConsole extends Console
{
    protected const COMMAND_NAME = 'rest-api:generate:documentation';
    protected const DESCRIPTION = 'Generates documentation for enabled Rest API endpoints.';

    /**
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();

        $this->setName(static::COMMAND_NAME)
            ->setDescription(static::DESCRIPTION);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->getFactory()->getConfig()->isRestApiDocumentationGeneratorEnabled()) {
            $this->getFacade()->generateDocumentation();

            return static::CODE_SUCCESS;
        }

        $this->error('This command intended to be used non production environment only!');

        return static::CODE_ERROR;
    }
}
