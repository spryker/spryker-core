<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Console\Communication\Plugin\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Command\SignalableCommandInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use const SIGQUIT;
use const SIGTERM;

/**
 * @method \Spryker\Zed\Console\Business\ConsoleFacadeInterface getFacade()
 * @method \Spryker\Zed\Console\Communication\ConsoleCommunicationFactory getFactory()
 */
class MultiProcessRunConsole extends Console implements SignalableCommandInterface
{
    /**
     * @var string
     */
    protected const COMMAND_NAME = 'multi-process:run';

    /**
     * @var string
     */
    protected const DESCRIPTION = 'Runs child console commands in a loop. Example of usage: vendor/bin/console multi-process:run "queue:worker:start publish" 120 -t -m 2 -t 30';

    /**
     * @var string
     */
    protected const ARGUMENT_CONSOLE_CHILD = 'child';

    /**
     * @var string
     */
    protected const ARGUMENT_CONSOLE_TOTAL_TIMEOUT = 'total_timeout';

    /**
     * @var int
     */
    protected const DEFAULT_TOTAL_TIMEOUT_IN_SECONDS = 600;

    /**
     * @var int
     */
    protected const DEFAULT_CHILD_PROCESS_TIMEOUT = 60;

    /**
     * @var int
     */
    protected const DEFAULT_CHILD_MINIMUM_DURATION_TIME_SECONDS = 0;

    /**
     * @var string
     */
    protected const OPTION_SEPARATE_THREAD = 'separate_thread';

    /**
     * @var string
     */
    protected const OPTION_CHILD_MINIMUM_DURATION_TIME_SECONDS = 'child_min_duration';

    /**
     * @var string
     */
    protected const OPTION_CHILD_PROCESS_TIMEOUT = 'child_timeout';

    /**
     * @var string
     */
    protected const RUNNER_COMMAND = APPLICATION_VENDOR_DIR . '/bin/console ';

    /**
     * @var bool
     */
    protected bool $shouldStop = false;

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription(static::DESCRIPTION);
        $this->addArgument(static::ARGUMENT_CONSOLE_CHILD, InputArgument::REQUIRED, 'The full command you want to run as a child.');
        $this->addArgument(static::ARGUMENT_CONSOLE_TOTAL_TIMEOUT, InputArgument::OPTIONAL, sprintf('Duration Total Time in seconds while child command can start again, default is %s. Set 0 to run process endless', static::DEFAULT_TOTAL_TIMEOUT_IN_SECONDS), static::DEFAULT_TOTAL_TIMEOUT_IN_SECONDS);
        $this->addOption(static::OPTION_SEPARATE_THREAD, 's', InputOption::VALUE_OPTIONAL, 'Run the command in the separate process to eliminate process cache collisions.', false);
        $this->addOption(static::OPTION_CHILD_PROCESS_TIMEOUT, 't', InputOption::VALUE_OPTIONAL, 'Set maximum time of execution for child sub process, in seconds.', static::DEFAULT_CHILD_PROCESS_TIMEOUT);
        $this->addOption(static::OPTION_CHILD_MINIMUM_DURATION_TIME_SECONDS, 'm', InputOption::VALUE_OPTIONAL, 'Minimal child sub process execution time, in seconds. Skip or set to 0 to disable this check.', static::DEFAULT_CHILD_MINIMUM_DURATION_TIME_SECONDS);

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = $output;
        $maxDuration = abs((int)$input->getArgument(static::ARGUMENT_CONSOLE_TOTAL_TIMEOUT));

        return $this->runSubProcess($maxDuration);
    }

    /**
     * @param int $maxDuration
     *
     * @return int
     */
    protected function runSubProcess(int $maxDuration): int
    {
        $isLimited = $maxDuration > 0;
        $startTime = microtime(true);
        $lastChildDuration = 0;
        $predictedExecutionTime = 0;
        while ((!$isLimited || $predictedExecutionTime < $maxDuration) && !$this->shouldStop) {
            $diffTime = microtime(true) - $startTime;
            $predictedExecutionTime = $diffTime + $lastChildDuration;
            if ($this->output->isVerbose()) {
                $this->info(sprintf('<fg=green>Starting %schild process. Timer: %s </>', $isLimited ? '' : 'endless ', $diffTime));
            }

            $startTimeChild = microtime(true);
            $result = $this->runProcess();
            if ($result === static::CODE_ERROR) {
                return static::CODE_ERROR;
            }
            $lastChildDuration = microtime(true) - $startTimeChild;
            if ($this->output->isVerbose()) {
                $this->info('<fg=magenta>Child process executed. Timer: ' . ($diffTime + $lastChildDuration) . '</>');
            }
            $this->waitTillMinExecutionTime($lastChildDuration);
        }

        return static::CODE_SUCCESS;
    }

    /**
     * @param string $command
     *
     * @return \Symfony\Component\Process\Process
     */
    protected function createProcess(string $command): Process
    {
        $processTimeout = $this->getSubprocessTimeout();

        return Process::fromShellCommandline(
            static::RUNNER_COMMAND . $command,
            APPLICATION_ROOT_DIR,
            null,
            null,
            $processTimeout,
        );
    }

    /**
     * @param float $duration
     *
     * @return void
     */
    protected function waitTillMinExecutionTime(float $duration): void
    {
        $minExecutionTime = abs((int)$this->input->getOption(static::OPTION_CHILD_MINIMUM_DURATION_TIME_SECONDS));
        if ($duration < $minExecutionTime) {
            if ($this->output->isVerbose()) {
                $this->info(
                    sprintf('<fg=yellow>Process waiting as not reached minimal execution time %ss (duration: %ss)', $minExecutionTime, $duration),
                );
            }

            usleep((int)(($minExecutionTime - $duration) * 1e6));
        }
    }

    /**
     * @return int
     */
    protected function runProcess(): int
    {
        $separateThread = filter_var($this->input->getOption(static::OPTION_SEPARATE_THREAD), FILTER_VALIDATE_BOOLEAN);
        if ($separateThread) {
            return $this->runCommandAsSeparateThread();
        }

        return $this->runCommandDirectly();
    }

    /**
     * @return int
     */
    protected function runCommandAsSeparateThread(): int
    {
        $command = $this->input->getArgument(static::ARGUMENT_CONSOLE_CHILD);
        $this->createProcess($command)->run(function ($direction, $data): void {
            if ($this->output->isVerbose()) {
                $this->info($data);
            }
        });

        return static::CODE_SUCCESS;
    }

    /**
     * @return int
     */
    protected function runCommandDirectly(): int
    {
        $child = $this->input->getArgument(static::ARGUMENT_CONSOLE_CHILD);
        $childArguments = array_filter(explode(' ', $child));
        $childCommandName = array_shift($childArguments);
        /** @var \Spryker\Zed\Kernel\Communication\Console\Console $childConsoleCommand */
        $childConsoleCommand = $this->getApplication()->find($childCommandName);
        $childConsoleCommandInput = $this->prepareChildConsoleCommandInput($childConsoleCommand);

        return $childConsoleCommand->run($childConsoleCommandInput, $this->output);
    }

    /**
     * @param \Spryker\Zed\Kernel\Communication\Console\Console $childConsoleCommand
     * @param array<string> $childArguments
     *
     * @return \Symfony\Component\Console\Input\ArrayInput
     */
    protected function prepareChildConsoleCommandInput(Console $childConsoleCommand, array $childArguments = []): ArrayInput
    {
        $filteredArgs = [];
        $options = [];

        foreach ($childArguments as $childArgument) {
            $isLongOption = str_starts_with($childArgument, '--');
            $isShortOption = str_starts_with($childArgument, '-');
            if ($isShortOption || $isLongOption) {
                $option = explode('=', $childArgument);
                if (
                    ($isLongOption && $childConsoleCommand->getDefinition()->hasOption($option[0]))
                    ||
                    ($isShortOption && $childConsoleCommand->getDefinition()->hasShortcut(ltrim($option[0], '-')))
                ) {
                    $options[$option[0]] = $option[1] ?? true;
                }

                continue;
            }

            $filteredArgs[] = $childArgument;
        }

        $arguments = [];
        $key = 0;
        foreach ($childConsoleCommand->getDefinition()->getArguments() as $name => $argument) {
            $arguments[$name] = $filteredArgs[$key] ?? $argument->getDefault();
            $key++;
        }

        $input = array_merge($arguments, $options);

        return new ArrayInput($input);
    }

    /**
     * @return float|null
     */
    protected function getSubprocessTimeout(): ?float
    {
        $processTimeout = $this->input->getOption(static::OPTION_CHILD_PROCESS_TIMEOUT);
        if (!$processTimeout) {
            return null;
        }

        return (float)$processTimeout;
    }

    /**
     * Added possibility to stop workers by signals a that process can be finished healthy.
     *
     * @return list<int>
     */
    public function getSubscribedSignals(): array
    {
        return [
            SIGTERM,
            SIGQUIT,
        ];
    }

    /**
     * @param int $signal
     *
     * @return int|false
     */
    public function handleSignal(int $signal): int|false
    {
        $this->shouldStop = true;
        if ($this->output->isVerbose()) {
            $this->info(sprintf('<fg=magenta>The %s signal was caught.</>', $signal));
        }

        return static::CODE_SUCCESS;
    }
}
