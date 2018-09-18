<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\GlueAnnotationAnalyzer;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\GlueAnnotationAnalyzerInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\PluginAnalyzer;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\PluginAnalyzerInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationGenerator;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationGeneratorInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationPathGenerator;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationPathGeneratorInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationSchemaGenerator;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationSchemaGeneratorInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Writer\RestApiDocumentationWriterInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Writer\YamlRestApiDocumentationWriter;
use Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToTextInflectorInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToYamlDumperInterface;
use Spryker\Zed\RestApiDocumentationGenerator\RestApiDocumentationGeneratorDependencyProvider;

/**
 * @method \Spryker\Zed\RestApiDocumentationGenerator\RestApiDocumentationGeneratorConfig getConfig()
 */
class RestApiDocumentationGeneratorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationGeneratorInterface
     */
    public function createGenerator(): RestApiDocumentationGeneratorInterface
    {
        return new RestApiDocumentationGenerator(
            $this->createPluginAnalyzer(),
            $this->createYamlRestApiDocumentationWriter()
        );
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationSchemaGeneratorInterface
     */
    public function createRestApiDocumentationSchemaGenerator(): RestApiDocumentationSchemaGeneratorInterface
    {
        return new RestApiDocumentationSchemaGenerator($this->getResourceRelationshipsCollectionProviderPlugin());
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationPathGeneratorInterface
     */
    public function createRestApiDocumentationPathsGenerator(): RestApiDocumentationPathGeneratorInterface
    {
        return new RestApiDocumentationPathGenerator(
            $this->getConfig(),
            $this->createRestApiDocumentationSchemaGenerator(),
            $this->createGlueAnnotationAnalyzer()
        );
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Writer\RestApiDocumentationWriterInterface
     */
    public function createYamlRestApiDocumentationWriter(): RestApiDocumentationWriterInterface
    {
        return new YamlRestApiDocumentationWriter(
            $this->getConfig(),
            $this->getYamlDumper()
        );
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\GlueAnnotationAnalyzerInterface
     */
    public function createGlueAnnotationAnalyzer(): GlueAnnotationAnalyzerInterface
    {
        return new GlueAnnotationAnalyzer();
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\PluginAnalyzerInterface
     */
    public function createPluginAnalyzer(): PluginAnalyzerInterface
    {
        return new PluginAnalyzer(
            $this->createRestApiDocumentationPathsGenerator(),
            $this->createRestApiDocumentationSchemaGenerator(),
            $this->getResourceRoutesPluginsProviderPlugins(),
            $this->getResourceRelationshipsCollectionProviderPlugin(),
            $this->createGlueAnnotationAnalyzer(),
            $this->getTextInflector()
        );
    }

    /**
     * @return \Spryker\Glue\RestApiDocumentationGeneratorExtension\Dependency\Plugin\ResourceRoutePluginsProviderPluginInterface[]
     */
    public function getResourceRoutesPluginsProviderPlugins(): array
    {
        return $this->getProvidedDependency(RestApiDocumentationGeneratorDependencyProvider::PLUGIN_RESOURCE_ROUTE_PLUGINS_PROVIDERS);
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToYamlDumperInterface
     */
    public function getYamlDumper(): RestApiDocumentationGeneratorToYamlDumperInterface
    {
        return $this->getProvidedDependency(RestApiDocumentationGeneratorDependencyProvider::YAML_DUMPER);
    }

    /**
     * @return \Spryker\Glue\RestApiDocumentationGeneratorExtension\Dependency\Plugin\ResourceRelationshipCollectionProviderPluginInterface[]
     */
    public function getResourceRelationshipsCollectionProviderPlugin(): array
    {
        return $this->getProvidedDependency(RestApiDocumentationGeneratorDependencyProvider::PLUGIN_RESOURCE_RELATIONSHIPS_COLLECTION_PROVIDER);
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToTextInflectorInterface
     */
    public function getTextInflector(): RestApiDocumentationGeneratorToTextInflectorInterface
    {
        return $this->getProvidedDependency(RestApiDocumentationGeneratorDependencyProvider::TEXT_INFLECTOR);
    }
}
