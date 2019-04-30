<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Config\Communication\Plugin\WebProfiler;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Config\Profiler\ConfigProfilerCollector;
use Spryker\Zed\WebProfilerExtension\Dependency\Plugin\WebProfilerDataCollectorPluginInterface;
use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;

class WebProfilerConfigDataCollector implements WebProfilerDataCollectorPluginInterface
{
    public const SPRYKER_CONFIG_PROFILER = 'spryker_config_profiler';

    /**
     * @return string
     */
    public function getName(): string
    {
        return static::SPRYKER_CONFIG_PROFILER;
    }

    /**
     * @return string
     */
    public function getTemplateName(): string
    {
        return '@Config/Collector/spryker_config_profiler.html.twig';
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface
     */
    public function getDataCollector(ContainerInterface $container): DataCollectorInterface
    {
        return new ConfigProfilerCollector(Config::getProfileData());
    }
}
