<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Propel\Business\PropelFacadeInterface getFacade()
 */
class PropelSchemaValidatorConsole extends Console
{
    public const COMMAND_NAME = 'propel:schema:validate';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription('Validates the schema files.');

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $schemaValidationTransfer = $this->getFacade()->validateSchemaFiles();
        if ($schemaValidationTransfer->getIsSuccess()) {
            return static::CODE_SUCCESS;
        }

        foreach ($schemaValidationTransfer->getValidationErrors() as $validationErrorTransfer) {
            $output->writeln($validationErrorTransfer->getMessage());
        }

        return static::CODE_ERROR;
    }
}
