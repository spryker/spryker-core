<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\WebProfilerExtension\Dependency\Plugin;

use Spryker\Service\Container\ContainerInterface;
use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;

interface WebProfilerDataCollectorPluginInterface
{
    /**
     * Specification:
     * - Returns a name for this data collector.
     * - Name is used to map the collected data to a template.
     *
     * @api
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Specification:
     * - Returns a template path to be used for the collected data.
     *
     * @api
     *
     * @return string
     */
    public function getTemplateName(): string;

    /**
     * Specification:
     * - Returns a data collector which collects the data for the profile page.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface
     */
    public function getDataCollector(ContainerInterface $container): DataCollectorInterface;
}
