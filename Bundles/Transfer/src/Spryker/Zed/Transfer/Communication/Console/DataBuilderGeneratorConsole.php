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
class DataBuilderGeneratorConsole extends Console
{
    public const COMMAND_NAME = 'transfer:databuilder:generate';
    public const COMMAND_DESCRIPTION = 'Generates data builders from databuilder XML definition files';

    /**
     * @return void
     */
    protected function configure()
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
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $facade = $this->getFacade();
        $messenger = $this->getMessenger();

        $facade->deleteGeneratedDataBuilderObjects();
        $facade->generateDataBuilders($messenger);

        return static::CODE_SUCCESS;
    }
}
