<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business\Command\Filter;

use Generated\Shared\Transfer\SchedulerFilterTransfer;
use Spryker\Zed\Scheduler\Business\Command\Exception\SchedulerNotEnabledException;
use Spryker\Zed\Scheduler\Business\Command\Exception\SchedulerNotRegisteredException;
use Spryker\Zed\Scheduler\SchedulerConfig;

class SchedulerFilter implements SchedulerFilterInterface
{
    /**
     * @var \Spryker\Zed\Scheduler\SchedulerConfig
     */
    protected $schedulerConfig;

    /**
     * @var \Spryker\Zed\SchedulerExtension\Dependency\Plugin\SchedulerAdapterPluginInterface[]
     */
    protected $schedulerAdapterPlugins;

    /**
     * @param \Spryker\Zed\Scheduler\SchedulerConfig $schedulerConfig
     * @param \Spryker\Zed\SchedulerExtension\Dependency\Plugin\SchedulerAdapterPluginInterface[] $schedulerAdapterPlugins
     */
    public function __construct(SchedulerConfig $schedulerConfig, array $schedulerAdapterPlugins)
    {
        $this->schedulerConfig = $schedulerConfig;
        $this->schedulerAdapterPlugins = $schedulerAdapterPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerFilterTransfer $filterTransfer
     *
     * @return \Spryker\Zed\SchedulerExtension\Dependency\Plugin\SchedulerAdapterPluginInterface[]
     */
    public function getFilteredSchedulerAdapters(SchedulerFilterTransfer $filterTransfer): array
    {
        $enabledSchedulers = $this->schedulerConfig->getEnabledSchedulers();
        $filteredSchedulers = $filterTransfer->getSchedulers() ?: $enabledSchedulers;

        $this->assertConfiguredSchedulers($enabledSchedulers);
        $this->assertFilteredSchedulers($filteredSchedulers);

        return array_intersect_key(
            $this->schedulerAdapterPlugins,
            array_flip($filteredSchedulers),
            array_flip($enabledSchedulers)
        );
    }

    /**
     * @param array $enabledSchedulers
     *
     * @throws \Spryker\Zed\Scheduler\Business\Command\Exception\SchedulerNotRegisteredException
     *
     * @return void
     */
    protected function assertConfiguredSchedulers(array $enabledSchedulers): void
    {
        foreach ($enabledSchedulers as $idScheduler) {
            if (!array_key_exists($idScheduler, $this->schedulerAdapterPlugins)) {
                throw new SchedulerNotRegisteredException(sprintf(
                    'There is no adapter registered for `%s` defined in the configuration.',
                    $idScheduler
                ));
            }
        }
    }

    /**
     * @param array $filteredSchedulers
     *
     * @throws \Spryker\Zed\Scheduler\Business\Command\Exception\SchedulerNotEnabledException
     *
     * @return void
     */
    protected function assertFilteredSchedulers(array $filteredSchedulers): void
    {
        $enabledSchedulers = $this->schedulerConfig->getEnabledSchedulers();

        foreach ($filteredSchedulers as $idScheduler) {
            if (!in_array($idScheduler, $enabledSchedulers, true)) {
                throw new SchedulerNotEnabledException(sprintf(
                    'There is no enabled scheduler for `%s` defined in the request.',
                    $idScheduler
                ));
            }
        }
    }
}
