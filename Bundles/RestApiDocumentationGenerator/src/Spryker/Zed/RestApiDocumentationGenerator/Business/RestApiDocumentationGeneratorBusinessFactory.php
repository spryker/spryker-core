<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business;

use Spryker\Glue\RestApiDocumentationGeneratorExtension\Dependency\Plugin\ResourceRelationshipCollectionProviderPluginInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationGenerator;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationGeneratorInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationPathGenerator;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationPathGeneratorInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationSchemaGenerator;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationSchemaGeneratorInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Writer\RestApiDocumentationWriterInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Writer\YamlRestApiDocumentationWriter;
use Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToAnnotationsAnalyserInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToFinderInterface;
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
            $this->getResourceRoutesPluginsProviderPlugins(),
            $this->getResourceRelationshipsCollectionProviderPlugin(),
            $this->createRestApiDocumentationSchemaGenerator(),
            $this->createRestApiDocumentationPathsGenerator(),
            $this->createYamlRestApiDocumentationWriter()
        );
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationSchemaGeneratorInterface
     */
    public function createRestApiDocumentationSchemaGenerator(): RestApiDocumentationSchemaGeneratorInterface
    {
        return new RestApiDocumentationSchemaGenerator();
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationPathGeneratorInterface
     */
    public function createRestApiDocumentationPathsGenerator(): RestApiDocumentationPathGeneratorInterface
    {
        return new RestApiDocumentationPathGenerator(
            $this->getConfig(),
            $this->getAnnotationsAnalyser(),
            $this->getFinder()
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
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToAnnotationsAnalyserInterface
     */
    public function getAnnotationsAnalyser(): RestApiDocumentationGeneratorToAnnotationsAnalyserInterface
    {
        return $this->getProvidedDependency(RestApiDocumentationGeneratorDependencyProvider::ANNOTATION_ANALYSER);
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToFinderInterface
     */
    public function getFinder(): RestApiDocumentationGeneratorToFinderInterface
    {
        return $this->getProvidedDependency(RestApiDocumentationGeneratorDependencyProvider::FINDER);
    }

    /**
     * @return \Spryker\Glue\RestApiDocumentationGeneratorExtension\Dependency\Plugin\ResourceRelationshipCollectionProviderPluginInterface
     */
    public function getResourceRelationshipsCollectionProviderPlugin(): ResourceRelationshipCollectionProviderPluginInterface
    {
        return $this->getProvidedDependency(RestApiDocumentationGeneratorDependencyProvider::PLUGIN_RESOURCE_RELATIONSHIPS_COLLECTION_PROVIDER);
    }
}
