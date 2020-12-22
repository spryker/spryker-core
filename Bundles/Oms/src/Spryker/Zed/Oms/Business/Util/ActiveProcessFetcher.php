<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Util;

use Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface;

class ActiveProcessFetcher implements ActiveProcessFetcherInterface
{
    /**
     * @var \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject
     */
    protected $activeProcesses;

    /**
     * @var \Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface
     */
    protected $builder;

    /**
     * @var \Spryker\Zed\Oms\Business\Process\StateInterface[]
     */
    protected static $reservedStatesCache = [];

    /**
     * @var string[][]
     */
    protected static $reservedStateProcessNamesCache = [];

    /**
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $activeProcesses
     * @param \Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface $builder
     */
    public function __construct(
        ReadOnlyArrayObject $activeProcesses,
        BuilderInterface $builder
    ) {
        $this->activeProcesses = $activeProcesses;
        $this->builder = $builder;
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Process\StateInterface[]
     */
    public function getReservedStatesFromAllActiveProcesses(): array
    {
        if (!static::$reservedStatesCache) {
            static::$reservedStatesCache = $this->retrieveReservedStates();
        }

        return static::$reservedStatesCache;
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Process\StateInterface[]
     */
    protected function retrieveReservedStates(): array
    {
        $reservedStates = [];
        foreach ($this->activeProcesses as $processName) {
            $builder = clone $this->builder;
            $process = $builder->createProcess($processName);
            $reservedStates = array_merge($reservedStates, $process->getAllReservedStates());
        }

        return $reservedStates;
    }

    /**
     * @return string[][]
     */
    public function getReservedStateNamesWithMainActiveProcessNames(): array
    {
        if (!static::$reservedStateProcessNamesCache) {
            static::$reservedStateProcessNamesCache = $this->retrieveReservedStateNamesWithActiveMainProcessNames();
        }

        return static::$reservedStateProcessNamesCache;
    }

    /**
     * @return string[][]
     */
    protected function retrieveReservedStateNamesWithActiveMainProcessNames(): array
    {
        $reservedStateProcessNames = [];
        foreach ($this->activeProcesses as $processName) {
            $builder = clone $this->builder;
            $process = $builder->createProcess($processName);
            $reservedStateProcessNames = $this->prepareStateMainProcessNames(
                $process->getAllReservedStates(),
                $reservedStateProcessNames,
                $processName
            );
        }

        return $reservedStateProcessNames;
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\StateInterface[] $processReservedStates
     * @param string[][] $reservedStateProcessNames
     * @param string $processName
     *
     * @return string[][]
     */
    protected function prepareStateMainProcessNames(
        array $processReservedStates,
        array $reservedStateProcessNames,
        string $processName
    ): array {
        foreach ($processReservedStates as $reservedState) {
            $stateProcesses = $reservedStateProcessNames[$reservedState->getName()] ?? [];
            $reservedStateProcessNames[$reservedState->getName()] = array_merge($stateProcesses, [$processName]);
        }

        return $reservedStateProcessNames;
    }
}
