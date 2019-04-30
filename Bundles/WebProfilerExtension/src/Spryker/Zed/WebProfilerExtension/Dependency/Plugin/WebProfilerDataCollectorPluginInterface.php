<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WebProfilerExtension\Dependency\Plugin;

use Spryker\Service\Container\ContainerInterface;
use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;

interface WebProfilerDataCollectorPluginInterface
{
    /**
     * @api
     *
     * @return string
     */
    public function getName(): string;

    /**
     * @api
     *
     * @return string
     */
    public function getTemplateName(): string;

    /**
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface
     */
    public function getDataCollector(ContainerInterface $container): DataCollectorInterface;
}
