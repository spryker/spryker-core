<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\IndexGenerator\Communication\Console;

use Exception;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\IndexGenerator\Business\IndexGeneratorFacadeInterface getFacade()
 */
class PostgresIndexGeneratorConsole extends Console
{
    /**
     * @var string
     */
    protected const COMMAND_NAME = 'propel:postgres-indexes:generate';

    /**
     * @var string
     */
    protected const DESCRIPTION = 'Generates propel files with index definition for each foreign key, this is only relevant for postgres.';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME)
            ->setDescription(static::DESCRIPTION);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->getFacade()->removeIndexSchemaFiles();
            $this->getFacade()->generateIndexSchemaFiles();
        } catch (Exception $e) {
            $this->getMessenger()->error($e->getMessage());

            return static::CODE_ERROR;
        }

        return static::CODE_SUCCESS;
    }
}
