<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DocumentationGeneratorRestApi\Business;

use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\MockObject;
use SplFileInfo;
use Spryker\Glue\GlueApplication\Plugin\DocumentationGeneratorRestApi\ResourceRelationshipCollectionProviderPlugin;
use Spryker\Glue\GlueApplication\Plugin\DocumentationGeneratorRestApi\ResourceRoutePluginsProviderPlugin;
use Spryker\Glue\GlueApplication\Rest\Collection\ResourceRelationshipCollection;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface;
use Spryker\Service\UtilEncoding\UtilEncodingService;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\GlueAnnotationAnalyzer;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\GlueAnnotationAnalyzerInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourcePluginAnalyzer;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourcePluginAnalyzerInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceRelationshipsPluginAnalyzer;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceRelationshipsPluginAnalyzerInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceTransferAnalyzer;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceTransferAnalyzerInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Builder\OpenApiSpecificationSchemaComponentBuilder;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Builder\SchemaComponentBuilderInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Finder\GlueControllerFinder;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Finder\GlueControllerFinderInterface;
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
use Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToDoctrineInflectorAdapter;
use Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToFilesystemInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToFinderInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToSymfonyFilesystemAdapter;
use Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToSymfonyFinderAdapter;
use Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToSymfonyYamlAdapter;
use Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToTextInflectorInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToYamlDumperInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Dependency\Service\DocumentationGeneratorRestApiToUtilEncodingServiceBridge;
use Spryker\Zed\DocumentationGeneratorRestApi\Dependency\Service\DocumentationGeneratorRestApiToUtilEncodingServiceInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\DocumentationGeneratorRestApiConfig;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Stub\Plugin\TestResourceRelationshipPlugin;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Stub\Plugin\TestResourceRouteWithAllMethodsPlugin;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Stub\Plugin\TestResourceRouteWithEmptyAnnotationsPlugin;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Stub\Plugin\TestResourceRouteWithGetCollectionPlugin;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Stub\Plugin\TestResourceRouteWithGetResourceByIdPlugin;

class DocumentationGeneratorRestApiTestFactory extends Unit
{
    public const CONTROLLER_SOURCE_DIRECTORY = __DIR__ . '/Stub/Controller/';

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Business\Writer\DocumentationWriterInterface
     */
    public function createYamlOpenApiSpecificationWriter(): DocumentationWriterInterface
    {
        return new YamlOpenApiDocumentationWriter(
            $this->createConfig(),
            $this->createYamlDumper(),
            $this->createFilesystem()
        );
    }

    /**
     * @param array $sourceDirectories
     *
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Business\Finder\GlueControllerFinderInterface
     */
    public function createGlueControllerFinder(array $sourceDirectories): GlueControllerFinderInterface
    {
        return new GlueControllerFinder(
            $this->createFinder(),
            $this->createInflector(),
            $sourceDirectories
        );
    }

    /**
     * @param string $controller
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\DocumentationGeneratorRestApi\Business\Finder\GlueControllerFinderInterface
     */
    public function createGlueControllerFinderMock(string $controller): MockObject
    {
        $mock = $this->getMockBuilder(GlueControllerFinder::class)
            ->setMethods(['getGlueControllerFilesFromPlugin'])
            ->disableOriginalConstructor()
            ->getMock();
        $mock->method('getGlueControllerFilesFromPlugin')
            ->willReturn([$this->createControllerFileInfo($controller)]);

        return $mock;
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceRelationshipsPluginAnalyzerInterface
     */
    public function createResourceRelationshipsPluginAnalyzer(): ResourceRelationshipsPluginAnalyzerInterface
    {
        return new ResourceRelationshipsPluginAnalyzer([$this->createResourceRelationshipCollectionPluginMock()]);
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\GlueAnnotationAnalyzerInterface
     */
    public function createGlueAnnotationAnalyzer(): GlueAnnotationAnalyzerInterface
    {
        return new GlueAnnotationAnalyzer(
            $this->createGlueControllerFinder([static::CONTROLLER_SOURCE_DIRECTORY]),
            $this->createUtilEncodingService()
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\DocumentationGeneratorRestApiExtension\Dependency\Plugin\ResourceRelationshipCollectionProviderPluginInterface
     */
    public function createResourceRelationshipCollectionPluginMock(): MockObject
    {
        $pluginMock = $this->getMockBuilder(ResourceRelationshipCollectionProviderPlugin::class)
            ->setMethods(['getResourceRelationshipCollection'])
            ->getMock();
        $pluginMock->method('getResourceRelationshipCollection')
            ->willReturn($this->createResourceRelationshipCollection());

        return $pluginMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\DocumentationGeneratorRestApiExtension\Dependency\Plugin\ResourceRoutePluginsProviderPluginInterface
     */
    public function createResourceRoutePluginsProviderPluginMock(): MockObject
    {
        $pluginMock = $this->getMockBuilder(ResourceRoutePluginsProviderPlugin::class)
            ->setMethods(['getResourceRoutePlugins'])
            ->getMock();
        $pluginMock->method('getResourceRoutePlugins')
            ->willReturn([
                new TestResourceRouteWithAllMethodsPlugin(),
                new TestResourceRouteWithGetResourceByIdPlugin(),
                new TestResourceRouteWithEmptyAnnotationsPlugin(),
                new TestResourceRouteWithGetCollectionPlugin(),
            ]);

        return $pluginMock;
    }

    /**
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface
     */
    public function createResourceRelationshipCollection(): ResourceRelationshipCollectionInterface
    {
        $resourceRelationshipCollection = new ResourceRelationshipCollection();
        $resourceRelationshipCollection->addRelationship(
            'test-resource',
            new TestResourceRelationshipPlugin()
        );

        return $resourceRelationshipCollection;
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\SchemaGeneratorInterface
     */
    public function createOpenApiSpecificationSchemaGenerator(): SchemaGeneratorInterface
    {
        return new OpenApiSpecificationSchemaGenerator(
            $this->createResourceRelationshipsPluginAnalyzer(),
            $this->createResourceTransferAnalyzer(),
            $this->createOpenApiSpecificationSchemaComponentBuilder(),
            $this->createSchemaRenderer()
        );
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourcePluginAnalyzerInterface
     */
    public function createResourcePluginAnalyzer(): ResourcePluginAnalyzerInterface
    {
        return new ResourcePluginAnalyzer(
            $this->createRestApiMethodProcessor(),
            [$this->createResourceRoutePluginsProviderPluginMock()],
            $this->createGlueAnnotationAnalyzer(),
            $this->createInflector()
        );
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceTransferAnalyzerInterface
     */
    protected function createResourceTransferAnalyzer(): ResourceTransferAnalyzerInterface
    {
        return new ResourceTransferAnalyzer();
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\PathGeneratorInterface
     */
    public function createOpenApiSpecificationPathGenerator(): PathGeneratorInterface
    {
        return new OpenApiSpecificationPathGenerator($this->createPathMethodRenderer());
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
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\SecuritySchemeGeneratorInterface
     */
    public function createOpenApiSpecificationSecuritySchemeGenerator(): SecuritySchemeGeneratorInterface
    {
        return new OpenApiSpecificationSecuritySchemeGenerator($this->createSecuritySchemeRenderer());
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
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Business\Builder\SchemaComponentBuilderInterface
     */
    public function createOpenApiSpecificationSchemaComponentBuilder(): SchemaComponentBuilderInterface
    {
        return new OpenApiSpecificationSchemaComponentBuilder();
    }

    /**
     * @param string $controller
     *
     * @return \SplFileInfo
     */
    public function createControllerFileInfo(string $controller): SplFileInfo
    {
        return new SplFileInfo($controller);
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Dependency\Service\DocumentationGeneratorRestApiToUtilEncodingServiceInterface
     */
    public function createUtilEncodingService(): DocumentationGeneratorRestApiToUtilEncodingServiceInterface
    {
        return new DocumentationGeneratorRestApiToUtilEncodingServiceBridge(new UtilEncodingService());
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToFinderInterface
     */
    public function createFinder(): DocumentationGeneratorRestApiToFinderInterface
    {
        return new DocumentationGeneratorRestApiToSymfonyFinderAdapter();
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToTextInflectorInterface
     */
    public function createInflector(): DocumentationGeneratorRestApiToTextInflectorInterface
    {
        return new DocumentationGeneratorRestApiToDoctrineInflectorAdapter();
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToYamlDumperInterface
     */
    public function createYamlDumper(): DocumentationGeneratorRestApiToYamlDumperInterface
    {
        return new DocumentationGeneratorRestApiToSymfonyYamlAdapter();
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToFilesystemInterface
     */
    public function createFilesystem(): DocumentationGeneratorRestApiToFilesystemInterface
    {
        return new DocumentationGeneratorRestApiToSymfonyFilesystemAdapter();
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\DocumentationGeneratorRestApiConfig
     */
    public function createConfig(): DocumentationGeneratorRestApiConfig
    {
        return new DocumentationGeneratorRestApiConfig();
    }
}
