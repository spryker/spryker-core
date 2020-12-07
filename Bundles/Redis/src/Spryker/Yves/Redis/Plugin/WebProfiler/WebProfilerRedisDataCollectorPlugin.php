<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Redis\Plugin\WebProfiler;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\WebProfilerExtension\Dependency\Plugin\WebProfilerDataCollectorPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;

/**
 * @method \Spryker\Yves\Redis\RedisFactory getFactory()
 */
class WebProfilerRedisDataCollectorPlugin extends AbstractPlugin implements WebProfilerDataCollectorPluginInterface
{
    protected const DATA_COLLECTOR_NAME = 'redis';
    protected const DATA_TEMPLATE_NAME = '@Redis/redis';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return static::DATA_COLLECTOR_NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getTemplateName(): string
    {
        return static::DATA_TEMPLATE_NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface
     */
    public function getDataCollector(ContainerInterface $container): DataCollectorInterface
    {
        return $this->getFactory()->createRedisDataCollector();
    }
}
