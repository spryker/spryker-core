<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorApi;

use Spryker\Glue\DocumentationGeneratorApi\Dependency\External\DocumentationGeneratorApiToFilesystemInterface;
use Spryker\Glue\DocumentationGeneratorApi\Expander\ContextExpanderCollectionInterface;
use Spryker\Glue\DocumentationGeneratorApi\Generator\DocumentationGenerator;
use Spryker\Glue\DocumentationGeneratorApi\Generator\DocumentationGeneratorInterface;
use Spryker\Glue\DocumentationGeneratorApi\InvalidationVerifier\InvalidationVerifier;
use Spryker\Glue\DocumentationGeneratorApi\InvalidationVerifier\InvalidationVerifierInterface;
use Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\ContentGeneratorStrategyPluginInterface;
use Spryker\Glue\Kernel\AbstractFactory;

/**
 * @method \Spryker\Glue\DocumentationGeneratorApi\DocumentationGeneratorApiConfig getConfig()
 */
class DocumentationGeneratorApiFactory extends AbstractFactory
{
    /**
     * @return array<\Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\ApiApplicationProviderPluginInterface>
     */
    public function getApiApplicationProviderPlugins(): array
    {
        return $this->getProvidedDependency(DocumentationGeneratorApiDependencyProvider::PLUGINS_API_APPLICATION_PROVIDER);
    }

    /**
     * @return \Spryker\Glue\DocumentationGeneratorApi\Expander\ContextExpanderCollectionInterface
     */
    public function getContextExpanderPlugins(): ContextExpanderCollectionInterface
    {
        return $this->getProvidedDependency(DocumentationGeneratorApiDependencyProvider::PLUGINS_CONTEXT_EXPANDER);
    }

    /**
     * @return array<\Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\SchemaFormatterPluginInterface>
     */
    public function getSchemaFormatterPlugins(): array
    {
        return $this->getProvidedDependency(DocumentationGeneratorApiDependencyProvider::PLUGINS_SCHEMA_FORMATTER);
    }

    /**
     * @return \Spryker\Glue\DocumentationGeneratorApi\Dependency\External\DocumentationGeneratorApiToFilesystemInterface
     */
    public function getFilesystem(): DocumentationGeneratorApiToFilesystemInterface
    {
        return $this->getProvidedDependency(DocumentationGeneratorApiDependencyProvider::FILESYSTEM);
    }

    /**
     * @return \Spryker\Glue\DocumentationGeneratorApi\Generator\DocumentationGeneratorInterface
     */
    public function createDocumentationGenerator(): DocumentationGeneratorInterface
    {
        return new DocumentationGenerator(
            $this->getApiApplicationProviderPlugins(),
            $this->getContextExpanderPlugins(),
            $this->getFilesystem(),
            $this->getConfig(),
            $this->getSchemaFormatterPlugins(),
            $this->getContentGeneratorStrategyPlugin(),
        );
    }

    /**
     * @return \Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\ContentGeneratorStrategyPluginInterface
     */
    public function getContentGeneratorStrategyPlugin(): ContentGeneratorStrategyPluginInterface
    {
        return $this->getProvidedDependency(DocumentationGeneratorApiDependencyProvider::PLUGIN_CONTENT_GENERATOR_STRATEGY);
    }

    /**
     * @return \Spryker\Glue\DocumentationGeneratorApi\InvalidationVerifier\InvalidationVerifierInterface
     */
    public function createInvalidationVerifier(): InvalidationVerifierInterface
    {
        return new InvalidationVerifier(
            $this->getInvalidationVoterPlugins(),
            $this->getApiApplicationProviderPlugins(),
            $this->getConfig(),
        );
    }

    /**
     * @return array<\Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\DocumentationInvalidationVoterPluginInterface>
     */
    public function getInvalidationVoterPlugins(): array
    {
        return $this->getProvidedDependency(DocumentationGeneratorApiDependencyProvider::PLUGINS_INVALIDATION_VOTER);
    }
}
