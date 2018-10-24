<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RestApiDocumentationGenerator\Business;

use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\MockObject;
use SplFileInfo;
use Spryker\Glue\GlueApplication\Plugin\Rest\ResourceRelationshipCollectionProviderPlugin;
use Spryker\Glue\GlueApplication\Plugin\Rest\ResourceRoutePluginsProviderPlugin;
use Spryker\Glue\GlueApplication\Rest\Collection\ResourceRelationshipCollection;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface;
use Spryker\Service\UtilEncoding\UtilEncodingService;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\GlueAnnotationAnalyzer;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\GlueAnnotationAnalyzerInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\ResourcePluginAnalyzer;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\ResourcePluginAnalyzerInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\ResourceRelationshipsPluginAnalyzer;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\ResourceRelationshipsPluginAnalyzerInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\ResourceTransferAnalyzer;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\ResourceTransferAnalyzerInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Finder\GlueControllerFinder;
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
use Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToDoctrineInflectorAdapter;
use Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToFilesystemInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToFinderInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToSymfonyFilesystemAdapter;
use Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToSymfonyFinderAdapter;
use Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToSymfonyYamlAdapter;
use Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToTextInflectorInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToYamlDumperInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Dependency\Service\RestApiDocumentationGeneratorToUtilEncodingServiceBridge;
use Spryker\Zed\RestApiDocumentationGenerator\Dependency\Service\RestApiDocumentationGeneratorToUtilEncodingServiceInterface;
use Spryker\Zed\RestApiDocumentationGenerator\RestApiDocumentationGeneratorConfig;
use SprykerTest\Zed\RestApiDocumentationGenerator\Business\Stub\Plugin\TestResourceRelationshipPlugin;
use SprykerTest\Zed\RestApiDocumentationGenerator\Business\Stub\Plugin\TestResourceRouteWithAllMethodsPlugin;
use SprykerTest\Zed\RestApiDocumentationGenerator\Business\Stub\Plugin\TestResourceRouteWithEmptyAnnotationsPlugin;
use SprykerTest\Zed\RestApiDocumentationGenerator\Business\Stub\Plugin\TestResourceRouteWithGetCollectionPlugin;
use SprykerTest\Zed\RestApiDocumentationGenerator\Business\Stub\Plugin\TestResourceRouteWithGetResourceByIdPlugin;

class RestApiDocumentationGeneratorTestFactory extends Unit
{
    public const CONTROLLER_SOURCE_DIRECTORY = APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/RestApiDocumentationGenerator/tests/SprykerTest/Zed/RestApiDocumentationGenerator/Business/Stub/Controller/';

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Writer\RestApiDocumentationWriterInterface
     */
    public function createYamlRestApiDocumentationWriter(): RestApiDocumentationWriterInterface
    {
        return new YamlRestApiDocumentationWriter(
            $this->createConfig(),
            $this->createYamlDumper(),
            $this->createFilesystem()
        );
    }

    /**
     * @param array $sourceDirectories
     *
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Finder\GlueControllerFinder
     */
    public function createGlueControllerFinder(array $sourceDirectories): GlueControllerFinder
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
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\RestApiDocumentationGenerator\Business\Finder\GlueControllerFinderInterface
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
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\ResourceRelationshipsPluginAnalyzerInterface
     */
    public function createResourceRelationshipsPluginAnalyzer(): ResourceRelationshipsPluginAnalyzerInterface
    {
        return new ResourceRelationshipsPluginAnalyzer([$this->createResourceRelationshipCollectionPluginMock()]);
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\GlueAnnotationAnalyzerInterface
     */
    public function createGlueAnnotationAnalyzer(): GlueAnnotationAnalyzerInterface
    {
        return new GlueAnnotationAnalyzer(
            $this->createGlueControllerFinder([static::CONTROLLER_SOURCE_DIRECTORY]),
            $this->createUtilEncodingService()
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\RestApiDocumentationGeneratorExtension\Dependency\Plugin\ResourceRelationshipCollectionProviderPluginInterface
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
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\RestApiDocumentationGeneratorExtension\Dependency\Plugin\ResourceRoutePluginsProviderPluginInterface
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
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationSchemaGeneratorInterface
     */
    public function createSchemaGenerator(): RestApiDocumentationSchemaGeneratorInterface
    {
        return new RestApiDocumentationSchemaGenerator(
            $this->createResourceRelationshipsPluginAnalyzer(),
            $this->createResourceTransferAnalyzer(),
            $this->createSchemaRenderer()
        );
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\ResourcePluginAnalyzerInterface
     */
    public function createResourcePluginAnalyzer(): ResourcePluginAnalyzerInterface
    {
        return new ResourcePluginAnalyzer(
            $this->createPluginHandler(),
            [$this->createResourceRoutePluginsProviderPluginMock()],
            $this->createGlueAnnotationAnalyzer(),
            $this->createInflector()
        );
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\ResourceTransferAnalyzerInterface
     */
    protected function createResourceTransferAnalyzer(): ResourceTransferAnalyzerInterface
    {
        return new ResourceTransferAnalyzer();
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\SchemaRendererInterface
     */
    protected function createSchemaRenderer(): SchemaRendererInterface
    {
        return new SchemaRenderer($this->createSpecificationComponentValidator());
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationPathGeneratorInterface
     */
    public function createPathGenerator(): RestApiDocumentationPathGeneratorInterface
    {
        return new RestApiDocumentationPathGenerator($this->createPathMethodRenderer());
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\PathMethodRendererInterface
     */
    public function createPathMethodRenderer(): PathMethodRendererInterface
    {
        return new PathMethodRenderer($this->createSpecificationComponentValidator());
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Validator\SpecificationComponentValidatorInterface
     */
    public function createSpecificationComponentValidator(): SpecificationComponentValidatorInterface
    {
        return new SpecificationComponentValidator();
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationSecuritySchemeGeneratorInterface
     */
    public function createSecuritySchemeGenerator(): RestApiDocumentationSecuritySchemeGeneratorInterface
    {
        return new RestApiDocumentationSecuritySchemeGenerator($this->createSecuritySchemeRenderer());
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\SecuritySchemeRendererInterface
     */
    public function createSecuritySchemeRenderer(): SecuritySchemeRendererInterface
    {
        return new SecuritySchemeRenderer($this->createSpecificationComponentValidator());
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Handler\PluginHandlerInterface
     */
    public function createPluginHandler(): PluginHandlerInterface
    {
        return new PluginHandler(
            $this->createPathGenerator(),
            $this->createSchemaGenerator(),
            $this->createSecuritySchemeGenerator()
        );
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
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Dependency\Service\RestApiDocumentationGeneratorToUtilEncodingServiceInterface
     */
    public function createUtilEncodingService(): RestApiDocumentationGeneratorToUtilEncodingServiceInterface
    {
        return new RestApiDocumentationGeneratorToUtilEncodingServiceBridge(new UtilEncodingService());
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToFinderInterface
     */
    public function createFinder(): RestApiDocumentationGeneratorToFinderInterface
    {
        return new RestApiDocumentationGeneratorToSymfonyFinderAdapter();
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToTextInflectorInterface
     */
    public function createInflector(): RestApiDocumentationGeneratorToTextInflectorInterface
    {
        return new RestApiDocumentationGeneratorToDoctrineInflectorAdapter();
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToYamlDumperInterface
     */
    public function createYamlDumper(): RestApiDocumentationGeneratorToYamlDumperInterface
    {
        return new RestApiDocumentationGeneratorToSymfonyYamlAdapter();
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToFilesystemInterface
     */
    public function createFilesystem(): RestApiDocumentationGeneratorToFilesystemInterface
    {
        return new RestApiDocumentationGeneratorToSymfonyFilesystemAdapter();
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\RestApiDocumentationGeneratorConfig
     */
    public function createConfig(): RestApiDocumentationGeneratorConfig
    {
        return new RestApiDocumentationGeneratorConfig();
    }
}
