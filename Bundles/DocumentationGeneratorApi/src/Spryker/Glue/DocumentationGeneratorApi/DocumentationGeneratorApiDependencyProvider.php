<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorApi;

use Spryker\Glue\DocumentationGeneratorApi\Dependency\External\DocumentationGeneratorApiToFilesystemAdapter;
use Spryker\Glue\DocumentationGeneratorApi\Exception\MissingContentGeneratorStrategyException;
use Spryker\Glue\DocumentationGeneratorApi\Expander\ContextExpanderCollection;
use Spryker\Glue\DocumentationGeneratorApi\Expander\ContextExpanderCollectionInterface;
use Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\ContentGeneratorStrategyPluginInterface;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\DocumentationGeneratorApi\DocumentationGeneratorApiConfig getConfig()
 */
class DocumentationGeneratorApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FILESYSTEM = 'FILESYSTEM';

    /**
     * @var string
     */
    public const PLUGINS_API_APPLICATION_PROVIDER = 'PLUGINS_API_APPLICATION_PROVIDER';

    /**
     * @var string
     */
    public const PLUGINS_CONTEXT_EXPANDER = 'PLUGINS_CONTEXT_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_SCHEMA_FORMATTER = 'PLUGINS_SCHEMA_FORMATTER';

    /**
     * @var string
     */
    public const PLUGIN_CONTENT_GENERATOR_STRATEGY = 'PLUGIN_CONTENT_GENERATOR_STRATEGY';

    /**
     * @var string
     */
    public const PLUGINS_INVALIDATION_VOTER = 'PLUGINS_INVALIDATION_VOTER';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addApiApplicationProviderPlugins($container);
        $container = $this->addContextExpanderPlugins($container);
        $container = $this->addFilesystem($container);
        $container = $this->addSchemaFormatterPlugins($container);
        $container = $this->addContentGeneratorStrategyPlugin($container);
        $container = $this->addInvalidationVoterPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addApiApplicationProviderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_API_APPLICATION_PROVIDER, function () {
            return $this->getApiApplicationProviderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\ApiApplicationProviderPluginInterface>
     */
    protected function getApiApplicationProviderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addSchemaFormatterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SCHEMA_FORMATTER, function () {
            return $this->getSchemaFormatterPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\SchemaFormatterPluginInterface>
     */
    protected function getSchemaFormatterPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addContextExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CONTEXT_EXPANDER, function () {
            return $this->getContextExpanderPlugins(new ContextExpanderCollection());
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\DocumentationGeneratorApi\Expander\ContextExpanderCollectionInterface $contextExpanderCollection
     *
     * @return \Spryker\Glue\DocumentationGeneratorApi\Expander\ContextExpanderCollectionInterface
     */
    protected function getContextExpanderPlugins(ContextExpanderCollectionInterface $contextExpanderCollection): ContextExpanderCollectionInterface
    {
        return $contextExpanderCollection;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addFilesystem(Container $container): Container
    {
        $container->set(static::FILESYSTEM, function () {
            return new DocumentationGeneratorApiToFilesystemAdapter();
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addContentGeneratorStrategyPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_CONTENT_GENERATOR_STRATEGY, function () {
            return $this->getContentGeneratorStrategyPlugin();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\DocumentationInvalidationVoterPluginInterface>
     */
    protected function getInvalidationVoterPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addInvalidationVoterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_INVALIDATION_VOTER, function () {
            return $this->getInvalidationVoterPlugins();
        });

        return $container;
    }

    /**
     * @throws \Spryker\Glue\DocumentationGeneratorApi\Exception\MissingContentGeneratorStrategyException
     *
     * @return \Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\ContentGeneratorStrategyPluginInterface
     */
    protected function getContentGeneratorStrategyPlugin(): ContentGeneratorStrategyPluginInterface
    {
        throw new MissingContentGeneratorStrategyException(
            sprintf(
                'There is no registered content generator strategy plugin.
                    Make sure that DocumentationGeneratorApiDependencyProvider::getContentGeneratorStrategyPlugin() returns
                    an implementation of %s',
                ContentGeneratorStrategyPluginInterface::class,
            ),
        );
    }
}
