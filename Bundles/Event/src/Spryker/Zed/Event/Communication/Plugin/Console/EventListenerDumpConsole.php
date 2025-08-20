<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event\Communication\Plugin\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @method \Spryker\Zed\Event\Business\EventFacadeInterface getFacade()
 */
class EventListenerDumpConsole extends Console
{
    /**
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();

        $this
            ->setName('event:dump:listener')
            ->setDescription('Dump all configured event listeners for the Publish and Synchronize process.')
            ->addOption('event', null, InputOption::VALUE_REQUIRED, 'If set, only the the listeners for the given event name will be printed.')
            ->addOption('queue', null, InputOption::VALUE_REQUIRED, 'If set, only the the listeners for the given queue name will be printed.')
            ->addOption('event-names-only', null, InputOption::VALUE_NONE, 'If set, only the distinct event names will be printed.')
            ->addOption('queue-names-only', null, InputOption::VALUE_NONE, 'If set, only the distinct queue names will be printed.');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int Exit code
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $listener = $this->getFacade()->dumpEventListener();

        $isEventNamesOnlyRequest = $input->getOption('event-names-only');

        if ($isEventNamesOnlyRequest) {
            $io->writeln('<fg=yellow>Event Names:</>');

            foreach (array_keys($listener) as $eventName) {
                $io->writeln(sprintf('<fg=green>%s</>', $eventName));
            }

            return static::SUCCESS;
        }

        $isQueueNamesOnlyRequest = $input->getOption('queue-names-only');

        if ($isQueueNamesOnlyRequest) {
            $io->writeln('<fg=yellow>Queue Names:</>');

            $queueNames = [];

            foreach ($listener as $eventName => $listeners) {
                foreach ($listeners as $listenerContext) {
                    if ($listenerContext['queueName'] !== null) {
                        $queueNames[] = $listenerContext['queueName'];
                    }
                }
            }

            $queueNames = array_unique($queueNames);
            sort($queueNames);

            foreach ($queueNames as $queueName) {
                $io->writeln(sprintf('<fg=green>%s</>', $queueName));
            }

            return static::SUCCESS;
        }

        $requestedEventName = $input->getOption('event');
        $requestedQueueName = $input->getOption('queue');

        $listener = $this->filterListener($listener, $requestedEventName, $requestedQueueName);

        foreach ($listener as $eventName => $listeners) {
            $io->writeln(sprintf('  Event: <fg=yellow>%s</>', $eventName));

            $rows = [];

            foreach ($listeners as $listenerIdentifier => $listenerContext) {
                $rows[] = [
                    $listenerContext['queueName'] ?? 'N/A',
                    $listenerContext['queuePoolName'] ?? 'N/A',
                    $listenerIdentifier,
                    $listenerContext['isHandledInQueue'] ? 'Yes' : 'No',
                ];
            }

            $table = new Table($output);
            $table->setHeaders(['Queue Name', 'Queue Pool Name', 'Listener', 'Handled in Queue'])
                ->setRows($rows)
                ->setColumnWidth(0, 100)
                ->setColumnWidth(1, 30)
                ->setColumnWidth(2, 150)
                ->setColumnWidth(3, 10);
            $table->render();

            $io->newLine();
            $io->newLine();
        }

        return static::SUCCESS;
    }

    /**
     * @param array<string, array<string, array<string, string>>> $listener
     * @param string|null $requestedEventName
     * @param string|null $requestedQueueName
     *
     * @return array<string, array<string, array<string, string>>>
     */
    protected function filterListener(array $listener, ?string $requestedEventName, ?string $requestedQueueName): array
    {
        if (!$requestedEventName && !$requestedQueueName) {
            return $listener;
        }

        $filteredListener = [];

        foreach ($listener as $eventName => $listeners) {
            if ($requestedEventName && $eventName !== $requestedEventName) {
                continue;
            }

            foreach ($listeners as $listenerIdentifier => $listenerContext) {
                if ($requestedQueueName && $listenerContext['queueName'] !== $requestedQueueName) {
                    continue;
                }

                if (!isset($filteredListener[$eventName])) {
                    $filteredListener[$eventName] = [];
                }

                $filteredListener[$eventName][$listenerIdentifier] = $listenerContext;
            }
        }

        return $filteredListener;
    }
}
