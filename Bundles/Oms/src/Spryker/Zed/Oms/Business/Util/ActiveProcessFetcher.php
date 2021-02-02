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
     * @param string $processName
     *
     * @return string[]
     */
    public function getReservedStateNamesForActiveProcessByProcessName(string $processName): array
    {
        if (!isset(static::$reservedStateProcessNamesCache[$processName])) {
            static::$reservedStateProcessNamesCache[$processName] = $this->retrieveReservedStateNamesForActiveProcessByProcessName(
                $processName
            );
        }

        return static::$reservedStateProcessNamesCache[$processName];
    }

    /**
     * @param string $processName
     *
     * @return string[]
     */
    protected function retrieveReservedStateNamesForActiveProcessByProcessName(string $processName): array
    {
        if (!in_array($processName, $this->activeProcesses->getArrayCopy(), true)) {
            return [];
        }

        $builder = clone $this->builder;
        $process = $builder->createProcess($processName);

        return $this->getReservedStateNames($process->getAllReservedStates());
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\StateInterface[] $processReservedStates
     *
     * @return string[]
     */
    protected function getReservedStateNames(array $processReservedStates): array
    {
        $reservedStateProcessNames = [];

        foreach ($processReservedStates as $reservedState) {
            $reservedStateProcessNames[] = $reservedState->getName();
        }

        return $reservedStateProcessNames;
    }
}
