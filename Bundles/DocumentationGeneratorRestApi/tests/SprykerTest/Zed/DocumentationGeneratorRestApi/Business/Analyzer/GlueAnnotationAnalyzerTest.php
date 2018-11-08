<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Analyzer;

use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\MockObject;
use SplFileInfo;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\GlueAnnotationAnalyzer;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\GlueAnnotationAnalyzerInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Finder\GlueControllerFinder;
use Spryker\Zed\DocumentationGeneratorRestApi\Dependency\Service\DocumentationGeneratorRestApiToUtilEncodingServiceBridge;
use Spryker\Zed\DocumentationGeneratorRestApi\Dependency\Service\DocumentationGeneratorRestApiToUtilEncodingServiceInterface;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Stub\Plugin\TestResourceRoutePlugin;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Stub\RestTestAlternativeAttributesTransfer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group DocumentationGeneratorRestApi
 * @group Business
 * @group Analyzer
 * @group GlueAnnotationAnalyzerTest
 * Add your own group annotations below this line
 */
class GlueAnnotationAnalyzerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\DocumentationGeneratorRestApi\DocumentationGeneratorRestApiFacadeTester
     */
    protected $tester;

    protected const CONTROLLER_SOURCE_DIRECTORY = __DIR__ . '/../Stub/Controller/';
    protected const CONTROLLER_FILE_NAME = 'TestResourceController.php';

    protected const SUMMARY = 'Summary example';
    protected const HEADER_ACCEPT_LANGUAGE = 'Accept-Language';
    protected const KEY_RESPONSE_BAD_REQUEST = 400;
    protected const KEY_RESPONSE_NOT_FOUND = 404;
    protected const KEY_RESPONSE_SERVER_ERROR = 500;
    protected const VALUE_RESPONSE_BAD_REQUEST = 'Bad Request';
    protected const VALUE_RESPONSE_NOT_FOUND = 'Item not found';
    protected const VALUE_RESPONSE_SERVER_ERROR = 'Server Error';

    /**
     * @return void
     */
    public function testGetResourceParametersFromPluginWillReturnCorrectParameters(): void
    {
        $glueAnnotationAnalyzer = $this->createGlueAnnotationAnalyzer();
        $parameters = $glueAnnotationAnalyzer->getResourceParametersFromPlugin($this->createTestResourceRoutePlugin());

        $this->assertNotEmpty($parameters->getGetResource());
        $this->assertNotEmpty($parameters->getPost());
        $this->assertEmpty($parameters->getPatch());
        $this->assertEmpty($parameters->getGetCollection());
        $this->assertEmpty($parameters->getGetResourceById());
        $this->assertEmpty($parameters->getDelete());
        $this->assertEquals(static::SUMMARY, $parameters->getGetResource()->getSummary());
        $this->assertNotEmpty($parameters->getGetResource()->getParameters());
        $this->assertEquals(static::HEADER_ACCEPT_LANGUAGE, $parameters->getGetResource()->getParameters()[0]->getName());
        $this->assertNotEmpty($parameters->getGetResource()->getResponses());
        $this->assertArraySubset([
            static::KEY_RESPONSE_BAD_REQUEST => static::VALUE_RESPONSE_BAD_REQUEST,
            static::KEY_RESPONSE_NOT_FOUND => static::VALUE_RESPONSE_NOT_FOUND,
        ], $parameters->getGetResource()->getResponses());
        $this->assertNotEmpty($parameters->getPost()->getResponseClass());
        $this->assertEquals(RestTestAlternativeAttributesTransfer::class, $parameters->getPost()->getResponseClass());
        $this->assertArraySubset([
            static::KEY_RESPONSE_BAD_REQUEST => static::VALUE_RESPONSE_BAD_REQUEST,
            static::KEY_RESPONSE_SERVER_ERROR => static::VALUE_RESPONSE_SERVER_ERROR,
        ], $parameters->getPost()->getResponses());
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\GlueAnnotationAnalyzerInterface
     */
    protected function createGlueAnnotationAnalyzer(): GlueAnnotationAnalyzerInterface
    {
        return new GlueAnnotationAnalyzer(
            $this->getGlueControllerFinderMock(),
            $this->createDocumentationGeneratorRestApiToUtilEncodingService()
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\DocumentationGeneratorRestApi\Business\Finder\GlueControllerFinderInterface
     */
    protected function getGlueControllerFinderMock(): MockObject
    {
        $mock = $this->getMockBuilder(GlueControllerFinder::class)
            ->setMethods(['getGlueControllerFilesFromPlugin'])
            ->disableOriginalConstructor()
            ->getMock();
        $mock->method('getGlueControllerFilesFromPlugin')
            ->willReturn([$this->createControllerSplFileInfo()]);

        return $mock;
    }

    /**
     * @return \SplFileInfo
     */
    protected function createControllerSplFileInfo(): SplFileInfo
    {
        return new SplFileInfo(static::CONTROLLER_SOURCE_DIRECTORY . DIRECTORY_SEPARATOR . static::CONTROLLER_FILE_NAME);
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Dependency\Service\DocumentationGeneratorRestApiToUtilEncodingServiceInterface
     */
    protected function createDocumentationGeneratorRestApiToUtilEncodingService(): DocumentationGeneratorRestApiToUtilEncodingServiceInterface
    {
        return new DocumentationGeneratorRestApiToUtilEncodingServiceBridge(
            $this->tester->getLocator()->utilEncoding()->service()
        );
    }

    /**
     * @return \SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Stub\Plugin\TestResourceRoutePlugin
     */
    protected function createTestResourceRoutePlugin(): TestResourceRoutePlugin
    {
        return new TestResourceRoutePlugin();
    }
}
