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
use Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\ResourcePluginAnalyzer;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\ResourcePluginAnalyzerInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\ResourceRelationshipsPluginAnalyzer;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\ResourceRelationshipsPluginAnalyzerInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\ResourceTransferAnalyzer;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\ResourceTransferAnalyzerInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Finder\GlueControllerFinder;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Finder\GlueControllerFinderInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationGenerator;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationGeneratorInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationPathGenerator;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationPathGeneratorInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationSchemaGenerator;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationSchemaGeneratorInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationSecuritySchemeGenerator;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationSecuritySchemeGeneratorInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Handler\PluginHandler;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Handler\PluginHandlerInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\PathMethodRenderer;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\PathMethodRendererInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\SchemaRenderer;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\SchemaRendererInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\SecuritySchemeRenderer;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\SecuritySchemeRendererInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Validator\SpecificationComponentValidator;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Validator\SpecificationComponentValidatorInterface;
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
    public function createRestApiDocumentationGenerator(): RestApiDocumentationGeneratorInterface
    {
        return new RestApiDocumentationGenerator(
            $this->createResourcePluginAnalyzer(),
            $this->createYamlRestApiDocumentationWriter()
        );
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationSchemaGeneratorInterface
     */
    public function createRestApiDocumentationSchemaGenerator(): RestApiDocumentationSchemaGeneratorInterface
    {
        return new RestApiDocumentationSchemaGenerator(
            $this->createResourceRelationshipsPluginAnalyzer(),
            $this->createResourceTransferAnalyzer(),
            $this->createSchemaRenderer()
        );
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationSecuritySchemeGeneratorInterface
     */
    public function createRestApiDocumentationSecuritySchemaGenerator(): RestApiDocumentationSecuritySchemeGeneratorInterface
    {
        return new RestApiDocumentationSecuritySchemeGenerator($this->createSecuritySchemaRenderer());
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationPathGeneratorInterface
     */
    public function createRestApiDocumentationPathGenerator(): RestApiDocumentationPathGeneratorInterface
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
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\ResourcePluginAnalyzerInterface
     */
    public function createResourcePluginAnalyzer(): ResourcePluginAnalyzerInterface
    {
        return new ResourcePluginAnalyzer(
            $this->createPluginHandler(),
            $this->getResourceRoutesPluginsProviderPlugins(),
            $this->createGlueAnnotationAnalyzer(),
            $this->getTextInflector()
        );
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\ResourceRelationshipsPluginAnalyzerInterface
     */
    public function createResourceRelationshipsPluginAnalyzer(): ResourceRelationshipsPluginAnalyzerInterface
    {
        return new ResourceRelationshipsPluginAnalyzer($this->getResourceRelationshipsCollectionProviderPlugin());
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\ResourceTransferAnalyzerInterface
     */
    public function createResourceTransferAnalyzer(): ResourceTransferAnalyzerInterface
    {
        return new ResourceTransferAnalyzer();
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Handler\PluginHandlerInterface
     */
    public function createPluginHandler(): PluginHandlerInterface
    {
        return new PluginHandler(
            $this->createRestApiDocumentationPathGenerator(),
            $this->createRestApiDocumentationSchemaGenerator(),
            $this->createRestApiDocumentationSecuritySchemaGenerator()
        );
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Validator\SpecificationComponentValidatorInterface
     */
    public function createComponentValidator(): SpecificationComponentValidatorInterface
    {
        return new SpecificationComponentValidator();
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\PathMethodRendererInterface
     */
    public function createPathMethodRenderer(): PathMethodRendererInterface
    {
        return new PathMethodRenderer($this->createComponentValidator());
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\SchemaRendererInterface
     */
    public function createSchemaRenderer(): SchemaRendererInterface
    {
        return new SchemaRenderer($this->createComponentValidator());
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\SecuritySchemeRendererInterface
     */
    public function createSecuritySchemeRenderer(): SecuritySchemeRendererInterface
    {
        return new SecuritySchemeRenderer($this->createComponentValidator());
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
            $this->getTextInflector(),
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
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToTextInflectorInterface
     */
    public function getTextInflector(): RestApiDocumentationGeneratorToTextInflectorInterface
    {
        return $this->getProvidedDependency(RestApiDocumentationGeneratorDependencyProvider::TEXT_INFLECTOR);
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
}
