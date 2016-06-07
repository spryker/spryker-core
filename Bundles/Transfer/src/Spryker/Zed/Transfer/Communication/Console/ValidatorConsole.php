<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Communication\Console;

use Spryker\Zed\Console\Business\Model\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Transfer\Business\TransferFacade getFacade()
 */
class ValidatorConsole extends Console
{

    const COMMAND_NAME = 'transfer:validate';
    const OPTION_BUNDLE = 'bundle';
    const OPTION_CLEAN = 'clean';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName(self::COMMAND_NAME)
            ->setHelp('<info>' . self::COMMAND_NAME . ' -h</info>');

        $this->addOption(self::OPTION_BUNDLE, 'b', InputOption::VALUE_OPTIONAL, 'Name of core bundle to run PHPMD for (or "all")');
        $this->addOption(self::OPTION_CLEAN, 'c', InputOption::VALUE_NONE, 'Clean the file(s)');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @throws \Exception
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $messenger = $this->getMessenger();

        $result = $this->getFacade()->validateTransferObjects($messenger, $this->input->getOptions());
        return $result ? self::CODE_SUCCESS : self::CODE_ERROR;
    }

}
