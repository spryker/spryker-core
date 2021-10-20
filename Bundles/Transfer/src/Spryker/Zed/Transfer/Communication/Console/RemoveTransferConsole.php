<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Transfer\Business\TransferFacadeInterface getFacade()
 * @method \Spryker\Zed\Transfer\Communication\TransferCommunicationFactory getFactory()
 */
class RemoveTransferConsole extends Console
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'transfer:remove';

    /**
     * @var string
     */
    public const COMMAND_DESCRIPTION = 'Remove all generated data transfer objects';

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
        $transferFacade = $this->getFacade();

        $transferFacade->deleteGeneratedDataTransferObjects();

        return static::CODE_SUCCESS;
    }
}
