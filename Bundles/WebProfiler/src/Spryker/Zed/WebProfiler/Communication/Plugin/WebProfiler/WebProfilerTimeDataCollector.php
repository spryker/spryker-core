<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WebProfiler\Communication\Plugin\WebProfiler;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Zed\WebProfilerExtension\Dependency\Plugin\WebProfilerDataCollectorPluginInterface;
use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;
use Symfony\Component\HttpKernel\DataCollector\TimeDataCollector;

class WebProfilerTimeDataCollector implements WebProfilerDataCollectorPluginInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'time';
    }

    /**
     * @return string
     */
    public function getTemplateName(): string
    {
        return '@WebProfiler/Collector/time.html.twig';
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface
     */
    public function getDataCollector(ContainerInterface $container): DataCollectorInterface
    {
        return new TimeDataCollector(null, $container->get('stopwatch'));
    }
}
