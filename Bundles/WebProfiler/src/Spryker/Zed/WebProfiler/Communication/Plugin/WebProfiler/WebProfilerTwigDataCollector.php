<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WebProfiler\Communication\Plugin\WebProfiler;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Zed\WebProfilerExtension\Dependency\Plugin\WebProfilerDataCollectorPluginInterface;
use Symfony\Bridge\Twig\DataCollector\TwigDataCollector;
use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;
use Twig\Profiler\Profile;

class WebProfilerTwigDataCollector implements WebProfilerDataCollectorPluginInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'twig';
    }

    /**
     * @return string
     */
    public function getTemplateName(): string
    {
        return '@WebProfiler/Collector/twig.html.twig';
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface
     */
    public function getDataCollector(ContainerInterface $container): DataCollectorInterface
    {
        return new TwigDataCollector(new Profile());
    }
}
