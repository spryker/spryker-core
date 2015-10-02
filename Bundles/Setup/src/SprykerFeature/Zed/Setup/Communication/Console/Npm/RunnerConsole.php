<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Setup\Communication\Console\Npm;

use SprykerFeature\Zed\Console\Business\Model\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class RunnerConsole extends Console
{

    const COMMAND_NAME = 'code:npm';

    const NPM_COMMAND_TPL = 'npm run %s';

    const OPTION_TASK_BUILD_ALL = 'build-all';
    const OPTION_TASK_BUILD_ALL_SHORT = 'a';

    const OPTION_TASK_BUILD_CORE = 'build-core';
    const OPTION_TASK_BUILD_CORE_SHORT = 'c';

    const OPTION_TASK_BUILD_YVES = 'build-yves';
    const OPTION_TASK_BUILD_YVES_SHORT = 'y';

    const OPTION_TASK_BUILD_ZED = 'build-zed';
    const OPTION_TASK_BUILD_ZED_SHORT = 'z';

    /**
     * @var array
     */
    protected $commands = [
        self::OPTION_TASK_BUILD_ALL => 'spy-setup all',
        self::OPTION_TASK_BUILD_CORE => 'spy-setup core',
        self::OPTION_TASK_BUILD_ZED => 'spy-setup zed',
        self::OPTION_TASK_BUILD_YVES => 'spy-setup yves',
    ];

    /**
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('This command will execute \'npm run\' with the specified task');
        $this->setHelp(<<<EOM
This command will execute 'npm run' with a specified task.

Example:
 - code:npm --build-all  will build the client resources for the core, the project zed and the project yves code
EOM
        );

        $this->addOption(
            self::OPTION_TASK_BUILD_ALL,
            self::OPTION_TASK_BUILD_ALL_SHORT,
            InputOption::VALUE_NONE,
            'execute \'npm run\' to build all core and project resources'
        );

        $this->addOption(
            self::OPTION_TASK_BUILD_CORE,
            self::OPTION_TASK_BUILD_CORE_SHORT,
            InputOption::VALUE_NONE,
            'execute \'npm run\' to build the core resources of zed'
        );

        $this->addOption(
            self::OPTION_TASK_BUILD_ZED,
            self::OPTION_TASK_BUILD_ZED_SHORT,
            InputOption::VALUE_NONE,
            'execute \'npm run\' to build the project resources of zed'
        );

        $this->addOption(
            self::OPTION_TASK_BUILD_YVES,
            self::OPTION_TASK_BUILD_YVES_SHORT,
            InputOption::VALUE_NONE,
            'execute \'npm run\' to build the project resources of yves'
        );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = $this->getCommand();

        $this->runCommand($command);
    }

    /**
     * @return string
     */
    protected function getCommand()
    {
        $task = $this->getNpmTask();
        $command = sprintf(self::NPM_COMMAND_TPL, $this->commands[$task]);

        return $command;
    }

    /**
     * @param string $command
     */
    protected function runCommand($command)
    {
        $this->info('Run command: ' . $command);
        $process = new Process($command, APPLICATION_ROOT_DIR);
        $process->setTimeout(null);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
    }

    /**
     * @return string
     */
    protected function getNpmTask()
    {
        $tasks = [
            self::OPTION_TASK_BUILD_ALL,
            self::OPTION_TASK_BUILD_CORE,
            self::OPTION_TASK_BUILD_ZED,
            self::OPTION_TASK_BUILD_YVES,
        ];

        foreach ($tasks as $task) {
            $exists = $this->input->getOption($task);

            if ($exists) {
                return $task;
            }
        }

        return self::OPTION_TASK_BUILD_ALL;
    }

}
