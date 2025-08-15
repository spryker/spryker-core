<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication\Console;

use Exception;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Propel\Business\PropelFacadeInterface getFacade()
 * @method \Spryker\Zed\Propel\Communication\PropelCommunicationFactory getFactory()
 */
class SchemaCopyConsole extends Console
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'propel:schema:copy';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription('Copy schema files from packages to generated folder');

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
        $this->info('Clean schema directory');
        try {
            $this->getFacade()->cleanPropelSchemaDirectory();
        } catch (Exception $e) {
            $this->error($e->getMessage());
            $this->error('Please check project schema directory and try again.');

            return static::CODE_ERROR;
        }

        $this->info('Copy and merge schema files');
        try {
            $this->getFacade()->copySchemaFilesToTargetDirectory();
        } catch (Exception $e) {
            $this->error($e->getMessage());
            $this->error('Please check your schema files and try again.');

            return static::CODE_ERROR;
        }

        return static::CODE_SUCCESS;
    }
}
