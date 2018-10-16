<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Session\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Session\Business\SessionFacadeInterface getFacade()
 */
class SessionRemoveLockConsole extends Console
{
    public const COMMAND_NAME = 'session:lock:remove';
    public const OPTION_HELP = 'help';
    public const OPTION_APPLICATION_YVES = 'yves';
    public const OPTION_APPLICATION_ZED = 'zed';
    public const ARGUMENT_SESSION_ID = 'session_id';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription('Remove lock from session');
        $this->setHelp($this->getHelpText());

        $this->addOption(static::OPTION_APPLICATION_YVES, 'y', InputOption::VALUE_NONE, 'Handle Yves sessions');
        $this->addOption(static::OPTION_APPLICATION_ZED, 'z', InputOption::VALUE_NONE, 'Handle Zed sessions');
        $this->addArgument(static::ARGUMENT_SESSION_ID, InputArgument::REQUIRED, 'ID of session to handle locks for');
    }

    /**
     * @return string
     */
    protected function getHelpText()
    {
        return <<<'HELPTEXT'
For Yves:
session:lock:remove --yves <session_id>

For Zed:
session:lock:remove --zed <session_id>
HELPTEXT;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sessionId = $this->input->getArgument(static::ARGUMENT_SESSION_ID);

        $isYves = $this->input->getOption(static::OPTION_APPLICATION_YVES);
        if ($isYves) {
            $this->getFacade()->removeYvesSessionLockFor($sessionId);

            return 0;
        }

        $isZed = $this->input->getOption(static::OPTION_APPLICATION_ZED);
        if ($isZed) {
            $this->getFacade()->removeZedSessionLockFor($sessionId);

            return 0;
        }

        $this->getMessenger()->error('Missing application option (either --yves or --zed)');

        return 1;
    }
}
