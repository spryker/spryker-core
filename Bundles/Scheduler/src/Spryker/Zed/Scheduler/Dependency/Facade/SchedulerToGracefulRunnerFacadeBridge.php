<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Dependency\Facade;

use Generator;

class SchedulerToGracefulRunnerFacadeBridge implements SchedulerToGracefulRunnerFacadeInterface
{
    /**
     * @var \Spryker\Zed\GracefulRunner\Business\GracefulRunnerFacadeInterface
     */
    protected $gracefulRunnerFacade;

    /**
     * @param \Spryker\Zed\GracefulRunner\Business\GracefulRunnerFacadeInterface $gracefulRunnerFacade
     */
    public function __construct($gracefulRunnerFacade)
    {
        $this->gracefulRunnerFacade = $gracefulRunnerFacade;
    }

    /**
     * @param \Generator $generator
     *
     * @return int
     */
    public function run(Generator $generator): int
    {
        return $this->gracefulRunnerFacade->run($generator);
    }
}
