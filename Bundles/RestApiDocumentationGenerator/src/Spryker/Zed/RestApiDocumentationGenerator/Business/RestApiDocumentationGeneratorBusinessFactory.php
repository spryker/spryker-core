<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business;

use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\GlueAnnotationAnalyzer;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\GlueAnnotationAnalyzerInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\PluginAnalyzer;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\PluginAnalyzerInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Finder\GlueControllerFinder;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Finder\GlueControllerFinderInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationGenerator;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationGeneratorInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationPathGenerator;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationPathGeneratorInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationSchemaGenerator;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationSchemaGeneratorInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Handler\PluginHandler;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Handler\PluginHandlerInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\PathMethodPathRenderer;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\PathRendererInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\SchemaRenderer;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\SchemaRendererInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\SecuritySchemeRenderer;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Validator\ComponentValidator;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Validator\ComponentValidatorInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Writer\RestApiDocumentationWriterInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Writer\YamlRestApiDocumentationWriter;
use Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToFilesystemInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToFinderInterface;
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
        return new RestApiDocumentationSchemaGenerator(
            $this->getResourceRelationshipsCollectionProviderPlugin(),
            $this->createSchemaRenderer(),
            $this->createSecuritySchemaRenderer()
        );
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationPathGeneratorInterface
     */
    public function createRestApiDocumentationPathsGenerator(): RestApiDocumentationPathGeneratorInterface
    {
        return new RestApiDocumentationPathGenerator($this->createPathMethodRenderer());
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Writer\RestApiDocumentationWriterInterface
     */
    public function createYamlRestApiDocumentationWriter(): RestApiDocumentationWriterInterface
    {
        return new YamlRestApiDocumentationWriter(
            $this->getConfig(),
            $this->getYamlDumper(),
            $this->getFilesystem()
        );
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\GlueAnnotationAnalyzerInterface
     */
    public function createGlueAnnotationAnalyzer(): GlueAnnotationAnalyzerInterface
    {
        return new GlueAnnotationAnalyzer($this->createGlueControllerFinder());
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\PluginAnalyzerInterface
     */
    public function createPluginAnalyzer(): PluginAnalyzerInterface
    {
        return new PluginAnalyzer(
            $this->createPluginHandler(),
            $this->getResourceRoutesPluginsProviderPlugins(),
            $this->createGlueAnnotationAnalyzer(),
            $this->getTextInflector()
        );
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Handler\PluginHandlerInterface
     */
    public function createPluginHandler(): PluginHandlerInterface
    {
        return new PluginHandler(
            $this->createRestApiDocumentationPathsGenerator(),
            $this->createRestApiDocumentationSchemaGenerator()
        );
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Validator\ComponentValidatorInterface
     */
    public function createComponentValidator(): ComponentValidatorInterface
    {
        return new ComponentValidator();
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\PathRendererInterface
     */
    public function createPathMethodRenderer(): PathRendererInterface
    {
        return new PathMethodPathRenderer($this->createComponentValidator());
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\SchemaRendererInterface
     */
    public function createSchemaRenderer(): SchemaRendererInterface
    {
        return new SchemaRenderer($this->createComponentValidator());
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\SecuritySchemeRenderer
     */
    public function createSecuritySchemaRenderer(): SecuritySchemeRenderer
    {
        return new SecuritySchemeRenderer($this->createComponentValidator());
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Finder\GlueControllerFinderInterface
     */
    public function createGlueControllerFinder(): GlueControllerFinderInterface
    {
        return new GlueControllerFinder(
            $this->getFinder(),
            $this->getConfig()->getAnnotationsSourceDirectories()
        );
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToYamlDumperInterface
     */
    public function getYamlDumper(): RestApiDocumentationGeneratorToYamlDumperInterface
    {
        return $this->getProvidedDependency(RestApiDocumentationGeneratorDependencyProvider::YAML_DUMPER);
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToFilesystemInterface
     */
    public function getFilesystem(): RestApiDocumentationGeneratorToFilesystemInterface
    {
        return $this->getProvidedDependency(RestApiDocumentationGeneratorDependencyProvider::FILESYSTEM);
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToFinderInterface
     */
    public function getFinder(): RestApiDocumentationGeneratorToFinderInterface
    {
        return $this->getProvidedDependency(RestApiDocumentationGeneratorDependencyProvider::FINDER);
    }

    /**
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface
     */
    public function getResourceRouteCollection(): ResourceRouteCollectionInterface
    {
        return $this->getProvidedDependency(RestApiDocumentationGeneratorDependencyProvider::COLLECTION_RESOURCE_ROUTE);
    }

    /**
     * @return \Spryker\Glue\RestApiDocumentationGeneratorExtension\Dependency\Plugin\ResourceRoutePluginsProviderPluginInterface[]
     */
    public function getResourceRoutesPluginsProviderPlugins(): array
    {
        return $this->getProvidedDependency(RestApiDocumentationGeneratorDependencyProvider::PLUGIN_RESOURCE_ROUTE_PLUGINS_PROVIDERS);
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
