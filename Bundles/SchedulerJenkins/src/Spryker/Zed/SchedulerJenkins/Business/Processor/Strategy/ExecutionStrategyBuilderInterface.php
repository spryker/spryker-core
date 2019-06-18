<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Processor\Strategy;

use Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface;

interface ExecutionStrategyBuilderInterface
{
    /**
     * @param \Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface $configurationProvider
     *
     * @return \Spryker\Zed\SchedulerJenkins\Business\Processor\Strategy\ExecutionStrategyInterface
     */
    public function buildExecutionStrategy(ConfigurationProviderInterface $configurationProvider): ExecutionStrategyInterface;
}
