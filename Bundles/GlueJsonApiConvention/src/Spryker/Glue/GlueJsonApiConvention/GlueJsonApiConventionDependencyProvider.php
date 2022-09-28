<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueJsonApiConvention;

use Spryker\Glue\GlueJsonApiConvention\Dependency\External\GlueJsonApiConventionToInflectorAdapter;
use Spryker\Glue\GlueJsonApiConvention\Dependency\Service\GlueJsonApiConventionToUtilEncodingServiceBridge;
use Spryker\Glue\GlueJsonApiConvention\Plugin\GlueApplication\AttributesRequestBuilderPlugin;
use Spryker\Glue\GlueJsonApiConvention\Plugin\GlueApplication\FilterFieldRequestBuilderPlugin;
use Spryker\Glue\GlueJsonApiConvention\Plugin\GlueApplication\JsonApiResponseFormatterPlugin;
use Spryker\Glue\GlueJsonApiConvention\Plugin\GlueApplication\PaginationRequestBuilderPlugin;
use Spryker\Glue\GlueJsonApiConvention\Plugin\GlueApplication\RelationshipRequestBuilderPlugin;
use Spryker\Glue\GlueJsonApiConvention\Plugin\GlueApplication\RelationshipResponseFormatterPlugin;
use Spryker\Glue\GlueJsonApiConvention\Plugin\GlueApplication\SortRequestBuilderPlugin;
use Spryker\Glue\GlueJsonApiConvention\Plugin\GlueApplication\SparseFieldRequestBuilderPlugin;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\GlueJsonApiConvention\GlueJsonApiConventionConfig getConfig()
 */
class GlueJsonApiConventionDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @var string
     */
    public const PLUGINS_REQUEST_BUILDER = 'PLUGINS_REQUEST_BUILDER';

    /**
     * @var string
     */
    public const PLUGINS_REQUEST_VALIDATOR = 'PLUGINS_REQUEST_VALIDATOR';

    /**
     * @var string
     */
    public const PLUGINS_ROUTE_MATCHER = 'PLUGINS_ROUTE_MATCHER';

    /**
     * @var string
     */
    public const PLUGINS_REQUEST_AFTER_ROUTING_VALIDATOR = 'PLUGINS_REQUEST_AFTER_ROUTING_VALIDATOR';

    /**
     * @var string
     */
    public const PLUGINS_RESPONSE_FORMATTER = 'PLUGINS_RESPONSE_FORMATTER';

    /**
     * @var string
     */
    public const PLUGINS_RELATIONSHIP_PROVIDER = 'PLUGINS_RELATIONSHIP_PROVIDER';

    /**
     * @var string
     */
    public const INFLECTOR = 'INFLECTOR';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addRequestBuilderPlugins($container);
        $container = $this->addRequestValidatorPlugins($container);
        $container = $this->addRequestAfterRoutingValidatorPlugins($container);
        $container = $this->addResponseFormatterPlugins($container);
        $container = $this->addRelationshipProviderPlugins($container);
        $container = $this->addInflector($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new GlueJsonApiConventionToUtilEncodingServiceBridge($container->getLocator()->utilEncoding()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addRequestBuilderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_REQUEST_BUILDER, function () {
            return array_merge($this->getInternalRequestBuilderPlugins(), $this->getRequestBuilderPlugins());
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addRequestValidatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_REQUEST_VALIDATOR, function () {
            return $this->getRequestValidatorPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addRequestAfterRoutingValidatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_REQUEST_AFTER_ROUTING_VALIDATOR, function () {
            return $this->getRequestAfterRoutingValidatorPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addResponseFormatterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_RESPONSE_FORMATTER, function () {
            return array_merge($this->getInternalResponseFormatterPlugins(), $this->getResponseFormatterPlugins());
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addRelationshipProviderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_RELATIONSHIP_PROVIDER, function (Container $container) {
            return $this->getRelationshipProviderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addInflector(Container $container): Container
    {
        $container->set(static::INFLECTOR, function () {
            return new GlueJsonApiConventionToInflectorAdapter();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestBuilderPluginInterface>
     */
    protected function getRequestBuilderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface>
     */
    protected function getRequestValidatorPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface>
     */
    protected function getRequestAfterRoutingValidatorPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResponseFormatterPluginInterface>
     */
    protected function getResponseFormatterPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\RelationshipProviderPluginInterface>
     */
    public function getRelationshipProviderPlugins(): array
    {
        return [];
    }

    /**
     * @internal Should not be overwritten
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestBuilderPluginInterface>
     */
    protected function getInternalRequestBuilderPlugins(): array
    {
        return [
            new SparseFieldRequestBuilderPlugin(),
            new AttributesRequestBuilderPlugin(),
            new RelationshipRequestBuilderPlugin(),
            new PaginationRequestBuilderPlugin(),
            new SortRequestBuilderPlugin(),
            new FilterFieldRequestBuilderPlugin(),
        ];
    }

    /**
     * @internal Should not be overwritten
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResponseFormatterPluginInterface>
     */
    protected function getInternalResponseFormatterPlugins(): array
    {
        return [
            new RelationshipResponseFormatterPlugin(),
            new JsonApiResponseFormatterPlugin(),
        ];
    }
}
