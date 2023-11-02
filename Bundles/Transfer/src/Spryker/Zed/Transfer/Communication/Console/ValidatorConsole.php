<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Transfer\Business\TransferFacadeInterface getFacade()
 * @method \Spryker\Zed\Transfer\Communication\TransferCommunicationFactory getFactory()
 */
class ValidatorConsole extends Console
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'transfer:validate';

    /**
     * @var string
     */
    public const COMMAND_DESCRIPTION = 'Validates transfer XML definition files.';

    /**
     * @var string
     */
    public const OPTION_BUNDLE = 'bundle';

    /**
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();

        $description = static::COMMAND_DESCRIPTION;
        $description .= ' Use -vv for detailed debug output.';

        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription($description)
            ->setHelp('<info>' . static::COMMAND_NAME . ' -h</info>');

        $this->addOption(static::OPTION_BUNDLE, 'b', InputOption::VALUE_OPTIONAL, 'Name of core module to run validation for (defaults to all).');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $messenger = $this->getMessenger();

        $result = $this->getFacade()->validateTransferObjects($messenger, $this->input->getOptions());

        return $result ? static::CODE_SUCCESS : static::CODE_ERROR;
    }
}
