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
 * @method \Spryker\Zed\Propel\Communication\PropelCommunicationFactory getFactory()
 * @method \Spryker\Zed\Propel\Business\PropelFacadeInterface getFacade()
 */
class RemoveEntityTransferConsole extends Console
{
    public const COMMAND_NAME = 'transfer:entity:remove';
    public const COMMAND_DESCRIPTION = 'Removes generated entity transfer objects';

    /**
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();
        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->setHelp('<info>' . static::COMMAND_NAME . ' -h</info>');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $transferFacade = $this->getFactory()->getTransferFacade();

        $transferFacade->deleteGeneratedEntityTransferObjects();

        return static::CODE_SUCCESS;
    }
}
