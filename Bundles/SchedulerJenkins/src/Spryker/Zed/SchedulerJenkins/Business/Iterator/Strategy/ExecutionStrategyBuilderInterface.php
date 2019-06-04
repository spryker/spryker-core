<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Iterator\Strategy;

interface ExecutionStrategyBuilderInterface
{
    /**
     * @param string $idScheduler
     *
     * @return \Spryker\Zed\SchedulerJenkins\Business\Iterator\Strategy\ExecutionStrategyInterface
     */
    public function buildExecutionStrategy(string $idScheduler): ExecutionStrategyInterface;
}
