<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\HealthCheck;

use Spryker\Service\HealthCheck\Filter\Service\ServiceFilter;
use Spryker\Service\HealthCheck\Filter\Service\ServiceFilterInterface;
use Spryker\Service\HealthCheck\Format\ConsoleFormatter;
use Spryker\Service\HealthCheck\Format\Encoder\FormatEncoder;
use Spryker\Service\HealthCheck\Format\Encoder\FormatEncoderInterface;
use Spryker\Service\HealthCheck\Format\FormatterInterface;
use Spryker\Service\HealthCheck\Processor\HealthCheckServiceProcessor;
use Spryker\Service\HealthCheck\Processor\HealthCheckServiceProcessorInterface;
use Spryker\Service\Kernel\AbstractServiceFactory;

/**
 * @method \Spryker\Service\HealthCheck\HealthCheckConfig getConfig()
 */
class HealthCheckServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\HealthCheck\Processor\HealthCheckServiceProcessorInterface
     */
    public function createYvesHealthCheckServiceProcessor(): HealthCheckServiceProcessorInterface
    {
        return new HealthCheckServiceProcessor(
            $this->createYvesServiceFilter(),
            $this->createEncoder(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Service\HealthCheck\Filter\Service\ServiceFilterInterface
     */
    public function createYvesServiceFilter(): ServiceFilterInterface
    {
        return new ServiceFilter(
            $this->getYvesHealthCheckPlugins()
        );
    }

    /**
     * @return \Spryker\Service\HealthCheck\Processor\HealthCheckServiceProcessorInterface
     */
    public function createZedHealthCheckServiceProcessor(): HealthCheckServiceProcessorInterface
    {
        return new HealthCheckServiceProcessor(
            $this->createZedServiceFilter(),
            $this->createEncoder(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Service\HealthCheck\Filter\Service\ServiceFilterInterface
     */
    public function createZedServiceFilter(): ServiceFilterInterface
    {
        return new ServiceFilter(
            $this->getZedHealthCheckPlugins()
        );
    }

    /**
     * @return \Spryker\Service\HealthCheck\Processor\HealthCheckServiceProcessorInterface
     */
    public function createGlueHealthCheckServiceProcessor(): HealthCheckServiceProcessorInterface
    {
        return new HealthCheckServiceProcessor(
            $this->createGlueServiceFilter(),
            $this->createEncoder(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Service\HealthCheck\Filter\Service\ServiceFilterInterface
     */
    public function createGlueServiceFilter(): ServiceFilterInterface
    {
        return new ServiceFilter(
            $this->getGlueHealthCheckPlugins()
        );
    }

    /**
     * @return \Spryker\Service\HealthCheck\Format\Encoder\FormatEncoderInterface
     */
    public function createEncoder(): FormatEncoderInterface
    {
        return new FormatEncoder(
            $this->getEncoderFormatters()
        );
    }

    /**
     * @return \Spryker\Service\HealthCheck\Format\FormatterInterface[]
     */
    public function getEncoderFormatters(): array
    {
        return [
            $this->createConsoleFormatter(),
        ];
    }

    /**
     * @return \Spryker\Service\HealthCheck\Format\FormatterInterface
     */
    public function createConsoleFormatter(): FormatterInterface
    {
        return new ConsoleFormatter();
    }

    /**
     * @return \Spryker\Service\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    public function getYvesHealthCheckPlugins(): array
    {
        return $this->getProvidedDependency(HealthCheckDependencyProvider::PLUGINS_YVES_HEALTH_CHECK);
    }

    /**
     * @return \Spryker\Service\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    public function getZedHealthCheckPlugins(): array
    {
        return $this->getProvidedDependency(HealthCheckDependencyProvider::PLUGINS_ZED_HEALTH_CHECK);
    }

    /**
     * @return \Spryker\Service\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    public function getGlueHealthCheckPlugins(): array
    {
        return $this->getProvidedDependency(HealthCheckDependencyProvider::PLUGINS_GLUE_HEALTH_CHECK);
    }
}
