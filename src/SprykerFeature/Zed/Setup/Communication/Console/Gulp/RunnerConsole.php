<?php

namespace SprykerFeature\Zed\Setup\Communication\Console\Gulp;

use SprykerFeature\Zed\Console\Business\Model\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class RunnerConsole extends Console
{

    const COMMAND_NAME = 'code:gulp';

    const GULP_COMMAND_TPL_ZED = 'gulp %s --gulpfile config/Zed/gulpfile.js --cwd .';
    const GULP_COMMAND_TPL_YVES = 'gulp %s --gulpfile config/Yves/gulpfile.js --cwd .';

    const OPTION_APPLICATION = 'application';
    const OPTION_APPLICATION_SHORT = 'a';

    const OPTION_APPLICATION_YVES = 'yves';
    const OPTION_APPLICATION_YVES_SHORT = 'y';
    const OPTION_APPLICATION_ZED  = 'zed';
    const OPTION_APPLICATION_ZED_SHORT  = 'z';

    const OPTION_TASK = 'task';
    const OPTION_TASK_SHORT = 't';

    const OPTION_WATCH = 'watch';
    const OPTION_WATCH_SHORT = 'w';

    const WATCH_OPTION_ON = 'dev watcher';
    const WATCH_OPTION_OFF = 'dev';

    /**
     * @var array
     */
    protected $applications = [
        'Zed',
        'Yves'
    ];

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('This command will run gulp for all applications or for a specific application');
        $this->setHelp(<<<EOM
This command will run gulp for all applications or for a specific application

Examples:
 - gulp will run on all applications
 - gulp --application=zed will run only for Zed
   alias: gulp -zed
 - gulp --application=yves will run only for Yves
   alias: gulp -yves

You also have the option to set --watch as second parameter to activate the watch task at the end.
Note that this option can only be used if you run gulp for a specific application, not when you run both
EOM
        );
        $this->addOption(
            self::OPTION_APPLICATION,
            self::OPTION_APPLICATION_SHORT,
            InputOption::VALUE_OPTIONAL,
            'set the application for which command should run'
        );

        $this->addOption(
            self::OPTION_APPLICATION_YVES,
            self::OPTION_APPLICATION_YVES_SHORT,
            InputOption::VALUE_NONE,
            'run gulp for Yves; alias for --application Yves'
        );

        $this->addOption(
            self::OPTION_APPLICATION_ZED,
            self::OPTION_APPLICATION_ZED_SHORT,
            InputOption::VALUE_NONE,
            'run gulp for Zed; alias for --application Zed'
        );

        $this->addOption(
            self::OPTION_TASK,
            self::OPTION_TASK_SHORT,
            InputOption::VALUE_OPTIONAL,
            'run a specific gulp task'
        );

        $this->addOption(
            self::OPTION_WATCH,
            self::OPTION_WATCH_SHORT,
            InputOption::VALUE_NONE,
            'this will activate the watch task, this can only be activated if you run gulp for a specific application.'
        );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->updateNpm();

        $command = $this->getCommand();
        $this->runCommand($command);
    }

    protected function updateNpm()
    {
        $this->runCommand('npm install');
    }

    /**
     * @return string
     */
    protected function getCommand()
    {
        $tasks = $this->getGulpTasks();
        if ( ! $this->input->getOption(self::OPTION_APPLICATION) &&
             ! $this->input->getOption(self::OPTION_APPLICATION_YVES) &&
             ! $this->input->getOption(self::OPTION_APPLICATION_ZED) ) {
            $command = sprintf(self::GULP_COMMAND_TPL_ZED, $tasks);
            $command .= ' && ' . sprintf(self::GULP_COMMAND_TPL_YVES, $tasks);
        } else {
            if ( $this->input->getOption(self::OPTION_APPLICATION_YVES) ) {
                $application = 'yves';
            } elseif ( $this->input->getOption(self::OPTION_APPLICATION_ZED) ) {
                $application = 'zed';
            } else {
                $application = $this->input->getOption(self::OPTION_APPLICATION);
            }
            $application = $this->normalizeInputOption($application);
            $this->validateApplication($application);
            if ($application === 'Zed') {
                $command = sprintf(self::GULP_COMMAND_TPL_ZED, $tasks);
            } elseif ($application === 'Yves') {
                $command = sprintf(self::GULP_COMMAND_TPL_YVES, $tasks);
            }
        }
        return $command;
    }

    /**
     * @param $command
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
    protected function getGulpTasks()
    {
        if ( $this->input->getOption(self::OPTION_TASK)) {
            return $this->input->getOption(self::OPTION_TASK);
        }

        if ( $this->input->getOption(self::OPTION_WATCH) &&
             ! $this->input->getOption(self::OPTION_APPLICATION) &&
             ! $this->input->getOption(self::OPTION_APPLICATION_YVES) &&
             ! $this->input->getOption(self::OPTION_APPLICATION_ZED) ) {
            $this->error('You can use the watch option only if you run gulp for a specific application! Keep calm the task will run but watch will not be activated');
            return self::WATCH_OPTION_OFF;
        }
        if ($this->input->getOption(self::OPTION_WATCH)) {
            $tasks = self::WATCH_OPTION_ON;
        } else {
            $tasks = self::WATCH_OPTION_OFF;
        }
        return $tasks;
    }

    /**
     * @param $application
     * @return string
     */
    protected function normalizeInputOption($application)
    {
        return ucfirst(strtolower($application));
    }

    /**
     * @param $application
     * @throws \Exception
     */
    protected function validateApplication($application)
    {
        if (!in_array($application, $this->applications)) {
            throw new \Exception('Given application "' . $application . '" is not a valid application!');
        }
    }

}
