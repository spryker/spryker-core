<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Communication\Plugin\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\MessageBroker\Business\MessageBrokerFacadeInterface getFacade()
 */
class MessageBrokerDebugConsole extends Console
{
    /**
     * @var string
     */
    protected const COMMAND_NAME = 'message-broker:debug';

    /**
     * @var string
     */
    protected const COMMAND_DESCRIPTION = 'This command prints debug information about the message broker.';

    /**
     * @var string
     */
    public const OPTION_ASYNC_API_FILE = 'asyncapi-file';

    /**
     * @var string
     */
    public const OPTION_ASYNC_API_FILE_SHORT = 'a';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription(static::COMMAND_DESCRIPTION);
        $this->addOption(static::OPTION_ASYNC_API_FILE, static::OPTION_ASYNC_API_FILE_SHORT, InputOption::VALUE_REQUIRED, 'When a path to an AsyncAPI is passed the debug will run against this file.');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->getFacade()->printDebug($output, $this->findOptionAsyncApiFileValue($input));

        return static::CODE_SUCCESS;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return string|null
     */
    protected function findOptionAsyncApiFileValue(InputInterface $input): ?string
    {
        $optionAsyncApiFileValue = $input->getOption(static::OPTION_ASYNC_API_FILE);

        if ($optionAsyncApiFileValue === null || is_array($optionAsyncApiFileValue)) {
            return null;
        }

        return (string)$optionAsyncApiFileValue;
    }
}
