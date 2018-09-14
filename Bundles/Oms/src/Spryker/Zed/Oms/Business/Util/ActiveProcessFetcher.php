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
}
