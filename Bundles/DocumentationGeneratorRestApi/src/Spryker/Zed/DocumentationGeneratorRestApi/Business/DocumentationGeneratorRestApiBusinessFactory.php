<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business;

use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\GlueAnnotationAnalyzer;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\GlueAnnotationAnalyzerInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourcePluginAnalyzer;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourcePluginAnalyzerInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceRelationshipsPluginAnalyzer;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceRelationshipsPluginAnalyzerInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceTransferAnalyzer;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceTransferAnalyzerInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Builder\OpenApiSpecificationSchemaBuilder;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Builder\OpenApiSpecificationSchemaComponentBuilder;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Builder\SchemaBuilderInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Builder\SchemaComponentBuilderInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Finder\GlueControllerFinder;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Finder\GlueControllerFinderInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\DocumentationGenerator;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\DocumentationGeneratorInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\OpenApiSpecificationPathGenerator;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\OpenApiSpecificationSchemaGenerator;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\OpenApiSpecificationSecuritySchemeGenerator;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\PathGeneratorInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\SchemaGeneratorInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\SecuritySchemeGeneratorInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Processor\HttpMethodProcessor;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Processor\HttpMethodProcessorInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\PathMethodSpecificationComponent;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\PathMethodSpecificationComponentInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\PathParameterSpecificationComponent;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\PathParameterSpecificationComponentInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\PathRequestSpecificationComponent;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\PathRequestSpecificationComponentInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\PathResponseSpecificationComponent;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\PathResponseSpecificationComponentInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\SchemaPropertySpecificationComponent;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\SchemaPropertySpecificationComponentInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\SchemaSpecificationComponent;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\SchemaSpecificationComponentInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\SecuritySchemeSpecificationComponent;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\SecuritySchemeSpecificationComponentInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\PathMethodRenderer;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\PathMethodRendererInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\SchemaRenderer;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\SchemaRendererInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\SecuritySchemeRenderer;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\SecuritySchemeRendererInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Writer\DocumentationWriterInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Writer\YamlOpenApiDocumentationWriter;
use Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToFilesystemInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToFinderInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToTextInflectorInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToYamlDumperInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Dependency\Service\DocumentationGeneratorRestApiToUtilEncodingServiceInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\DocumentationGeneratorRestApiDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\DocumentationGeneratorRestApi\DocumentationGeneratorRestApiConfig getConfig()
 */
class DocumentationGeneratorRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\DocumentationGeneratorInterface
     */
    public function createDocumentationGenerator(): DocumentationGeneratorInterface
    {
        return new DocumentationGenerator(
            $this->createResourcePluginAnalyzer(),
            $this->createYamlOpenApiSpecificationWriter()
        );
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\SchemaGeneratorInterface
     */
    public function createOpenApiSpecificationSchemaGenerator(): SchemaGeneratorInterface
    {
        return new OpenApiSpecificationSchemaGenerator(
            $this->createResourceRelationshipsPluginAnalyzer(),
            $this->createResourceTransferAnalyzer(),
            $this->createOpenApiSpecificationSchemaBuilder(),
            $this->createSchemaRenderer()
        );
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\SecuritySchemeGeneratorInterface
     */
    public function createOpenApiSpecificationSecuritySchemeGenerator(): SecuritySchemeGeneratorInterface
    {
        return new OpenApiSpecificationSecuritySchemeGenerator($this->createSecuritySchemeRenderer());
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\PathGeneratorInterface
     */
    public function createOpenApiSpecificationPathGenerator(): PathGeneratorInterface
    {
        return new OpenApiSpecificationPathGenerator($this->createPathMethodRenderer());
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Business\Writer\DocumentationWriterInterface
     */
    public function createYamlOpenApiSpecificationWriter(): DocumentationWriterInterface
    {
        return new YamlOpenApiDocumentationWriter(
            $this->getConfig(),
            $this->getYamlDumper(),
            $this->getFilesystem()
        );
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\GlueAnnotationAnalyzerInterface
     */
    public function createGlueAnnotationAnalyzer(): GlueAnnotationAnalyzerInterface
    {
        return new GlueAnnotationAnalyzer(
            $this->createGlueControllerFinder(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourcePluginAnalyzerInterface
     */
    public function createResourcePluginAnalyzer(): ResourcePluginAnalyzerInterface
    {
        return new ResourcePluginAnalyzer(
            $this->createRestApiMethodProcessor(),
            $this->getResourceRoutesPluginProviderPlugins(),
            $this->createGlueAnnotationAnalyzer(),
            $this->getTextInflector()
        );
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceRelationshipsPluginAnalyzerInterface
     */
    public function createResourceRelationshipsPluginAnalyzer(): ResourceRelationshipsPluginAnalyzerInterface
    {
        return new ResourceRelationshipsPluginAnalyzer($this->getResourceRelationshipCollectionProviderPlugin());
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceTransferAnalyzerInterface
     */
    public function createResourceTransferAnalyzer(): ResourceTransferAnalyzerInterface
    {
        return new ResourceTransferAnalyzer();
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Business\Processor\HttpMethodProcessorInterface
     */
    public function createRestApiMethodProcessor(): HttpMethodProcessorInterface
    {
        return new HttpMethodProcessor(
            $this->createOpenApiSpecificationPathGenerator(),
            $this->createOpenApiSpecificationSchemaGenerator(),
            $this->createOpenApiSpecificationSecuritySchemeGenerator()
        );
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\PathMethodRendererInterface
     */
    public function createPathMethodRenderer(): PathMethodRendererInterface
    {
        return new PathMethodRenderer(
            $this->createPathMethodSpecificationComponent(),
            $this->createPathResponseSpecificationComponent(),
            $this->createPathRequestSpecificationComponent(),
            $this->createPathParameterSpecificationComponent()
        );
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\SchemaRendererInterface
     */
    public function createSchemaRenderer(): SchemaRendererInterface
    {
        return new SchemaRenderer(
            $this->createSchemaSpecificationComponent(),
            $this->createSchemaPropertySpecificationComponent()
        );
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\SecuritySchemeRendererInterface
     */
    public function createSecuritySchemeRenderer(): SecuritySchemeRendererInterface
    {
        return new SecuritySchemeRenderer($this->createSecuritySchemeSpecificationComponent());
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\PathMethodSpecificationComponentInterface
     */
    public function createPathMethodSpecificationComponent(): PathMethodSpecificationComponentInterface
    {
        return new PathMethodSpecificationComponent();
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\PathParameterSpecificationComponentInterface
     */
    public function createPathParameterSpecificationComponent(): PathParameterSpecificationComponentInterface
    {
        return new PathParameterSpecificationComponent();
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\PathRequestSpecificationComponentInterface
     */
    public function createPathRequestSpecificationComponent(): PathRequestSpecificationComponentInterface
    {
        return new PathRequestSpecificationComponent();
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\PathResponseSpecificationComponentInterface
     */
    public function createPathResponseSpecificationComponent(): PathResponseSpecificationComponentInterface
    {
        return new PathResponseSpecificationComponent();
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\SchemaPropertySpecificationComponentInterface
     */
    public function createSchemaPropertySpecificationComponent(): SchemaPropertySpecificationComponentInterface
    {
        return new SchemaPropertySpecificationComponent();
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\SchemaSpecificationComponentInterface
     */
    public function createSchemaSpecificationComponent(): SchemaSpecificationComponentInterface
    {
        return new SchemaSpecificationComponent();
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\SecuritySchemeSpecificationComponentInterface
     */
    public function createSecuritySchemeSpecificationComponent(): SecuritySchemeSpecificationComponentInterface
    {
        return new SecuritySchemeSpecificationComponent();
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Business\Finder\GlueControllerFinderInterface
     */
    public function createGlueControllerFinder(): GlueControllerFinderInterface
    {
        return new GlueControllerFinder(
            $this->getFinder(),
            $this->getTextInflector(),
            $this->getConfig()->getAnnotationSourceDirectories()
        );
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Business\Builder\SchemaBuilderInterface
     */
    public function createOpenApiSpecificationSchemaBuilder(): SchemaBuilderInterface
    {
        return new OpenApiSpecificationSchemaBuilder($this->createOpenApiSpecificationSchemaComponentBuilder());
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Business\Builder\SchemaComponentBuilderInterface
     */
    public function createOpenApiSpecificationSchemaComponentBuilder(): SchemaComponentBuilderInterface
    {
        return new OpenApiSpecificationSchemaComponentBuilder($this->createResourceTransferAnalyzer());
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Dependency\Service\DocumentationGeneratorRestApiToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): DocumentationGeneratorRestApiToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(DocumentationGeneratorRestApiDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToYamlDumperInterface
     */
    public function getYamlDumper(): DocumentationGeneratorRestApiToYamlDumperInterface
    {
        return $this->getProvidedDependency(DocumentationGeneratorRestApiDependencyProvider::YAML_DUMPER);
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToFilesystemInterface
     */
    public function getFilesystem(): DocumentationGeneratorRestApiToFilesystemInterface
    {
        return $this->getProvidedDependency(DocumentationGeneratorRestApiDependencyProvider::FILESYSTEM);
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToFinderInterface
     */
    public function getFinder(): DocumentationGeneratorRestApiToFinderInterface
    {
        return $this->getProvidedDependency(DocumentationGeneratorRestApiDependencyProvider::FINDER);
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToTextInflectorInterface
     */
    public function getTextInflector(): DocumentationGeneratorRestApiToTextInflectorInterface
    {
        return $this->getProvidedDependency(DocumentationGeneratorRestApiDependencyProvider::TEXT_INFLECTOR);
    }

    /**
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface
     */
    public function getResourceRouteCollection(): ResourceRouteCollectionInterface
    {
        return $this->getProvidedDependency(DocumentationGeneratorRestApiDependencyProvider::COLLECTION_RESOURCE_ROUTE);
    }

    /**
     * @return \Spryker\Glue\DocumentationGeneratorRestApiExtension\Dependency\Plugin\ResourceRoutePluginsProviderPluginInterface[]
     */
    public function getResourceRoutesPluginProviderPlugins(): array
    {
        return $this->getProvidedDependency(DocumentationGeneratorRestApiDependencyProvider::PLUGIN_RESOURCE_ROUTE_PLUGIN_PROVIDERS);
    }

    /**
     * @return \Spryker\Glue\DocumentationGeneratorRestApiExtension\Dependency\Plugin\ResourceRelationshipCollectionProviderPluginInterface[]
     */
    public function getResourceRelationshipCollectionProviderPlugin(): array
    {
        return $this->getProvidedDependency(DocumentationGeneratorRestApiDependencyProvider::PLUGIN_RESOURCE_RELATIONSHIP_COLLECTION_PROVIDER);
    }
}
