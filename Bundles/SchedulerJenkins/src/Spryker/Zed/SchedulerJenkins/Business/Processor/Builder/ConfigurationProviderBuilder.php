<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Processor\Builder;

use Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface;
use Spryker\Zed\SchedulerJenkins\Business\Processor\Configuration\ConfigurationProvider;
use Spryker\Zed\SchedulerJenkins\SchedulerJenkinsConfig;

class ConfigurationProviderBuilder implements ConfigurationProviderBuilderInterface
{
    /**
     * @var \Spryker\Zed\SchedulerJenkins\SchedulerJenkinsConfig
     */
    protected $schedulerJenkinsConfig;

    /**
     * @param \Spryker\Zed\SchedulerJenkins\SchedulerJenkinsConfig $schedulerJenkinsConfig
     */
    public function __construct(SchedulerJenkinsConfig $schedulerJenkinsConfig)
    {
        $this->schedulerJenkinsConfig = $schedulerJenkinsConfig;
    }

    /**
     * @param string $idScheduler
     *
     * @return \Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface
     */
    public function build(string $idScheduler): ConfigurationProviderInterface
    {
        return new ConfigurationProvider($idScheduler, $this->schedulerJenkinsConfig);
    }
}
