<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RestApiDocumentationGenerator\Business\Analyzer;

use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\MockObject;
use SplFileInfo;
use Spryker\Service\UtilEncoding\UtilEncodingService;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\GlueAnnotationAnalyzer;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\GlueAnnotationAnalyzerInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Finder\GlueControllerFinder;
use Spryker\Zed\RestApiDocumentationGenerator\Dependency\Service\RestApiDocumentationGeneratorToUtilEncodingServiceBridge;
use Spryker\Zed\RestApiDocumentationGenerator\Dependency\Service\RestApiDocumentationGeneratorToUtilEncodingServiceInterface;
use SprykerTest\Zed\RestApiDocumentationGenerator\Business\Stub\Plugin\TestResourceRoutePlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group RestApiDocumentationGenerator
 * @group Business
 * @group Analyzer
 * @group GlueAnnotationAnalyzerTest
 * Add your own group annotations below this line
 */
class GlueAnnotationAnalyzerTest extends Unit
{
    protected const CONTROLLER_SOURCE_DIRECTORY = APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/RestApiDocumentationGenerator/tests/SprykerTest/Zed/RestApiDocumentationGenerator/Business/Stub/Controller/';
    protected const CONTROLLER_FILE_NAME = 'TestResourceController.php';

    protected const SUMMARY = 'Summary example';
    protected const RESPONSE_CLASS = 'Generated\\Shared\\Transfer\\RestTokenResponseAttributesTransfer';
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
        $glueAnnotationAnalyzer = $this->getGlueAnnotationAnalyzer();
        $parameters = $glueAnnotationAnalyzer->getResourceParametersFromPlugin(new TestResourceRoutePlugin());

        $this->assertNotEmpty($parameters->getGetResource());
        $this->assertNotEmpty($parameters->getPost());
        $this->assertEmpty($parameters->getPatch());
        $this->assertEmpty($parameters->getGetCollection());
        $this->assertEmpty($parameters->getGetResourceById());
        $this->assertEmpty($parameters->getDelete());
        $this->assertEquals(static::SUMMARY, $parameters->getGetResource()->getSummary());
        $this->assertNotEmpty($parameters->getGetResource()->getHeaders());
        $this->assertEquals(static::HEADER_ACCEPT_LANGUAGE, $parameters->getGetResource()->getHeaders()[0]);
        $this->assertNotEmpty($parameters->getGetResource()->getResponses());
        $this->assertArraySubset([
            static::KEY_RESPONSE_BAD_REQUEST => static::VALUE_RESPONSE_BAD_REQUEST,
            static::KEY_RESPONSE_NOT_FOUND => static::VALUE_RESPONSE_NOT_FOUND,
        ], $parameters->getGetResource()->getResponses());
        $this->assertNotEmpty($parameters->getPost()->getResponseClass());
        $this->assertEquals(static::RESPONSE_CLASS, $parameters->getPost()->getResponseClass());
        $this->assertArraySubset([
            static::KEY_RESPONSE_BAD_REQUEST => static::VALUE_RESPONSE_BAD_REQUEST,
            static::KEY_RESPONSE_SERVER_ERROR => static::VALUE_RESPONSE_SERVER_ERROR,
        ], $parameters->getPost()->getResponses());
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\GlueAnnotationAnalyzerInterface
     */
    protected function getGlueAnnotationAnalyzer(): GlueAnnotationAnalyzerInterface
    {
        return new GlueAnnotationAnalyzer(
            $this->getGlueControllerFinder(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\RestApiDocumentationGenerator\Business\Finder\GlueControllerFinderInterface
     */
    protected function getGlueControllerFinder(): MockObject
    {
        $mock = $this->getMockBuilder(GlueControllerFinder::class)
            ->setMethods(['getGlueControllerFilesFromPlugin'])
            ->disableOriginalConstructor()
            ->getMock();
        $mock->method('getGlueControllerFilesFromPlugin')
            ->willReturn([$this->getControllerFileInfo()]);

        return $mock;
    }

    /**
     * @return \SplFileInfo
     */
    protected function getControllerFileInfo(): SplFileInfo
    {
        return new SplFileInfo(static::CONTROLLER_SOURCE_DIRECTORY . DIRECTORY_SEPARATOR . static::CONTROLLER_FILE_NAME);
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Dependency\Service\RestApiDocumentationGeneratorToUtilEncodingServiceInterface
     */
    protected function getUtilEncodingService(): RestApiDocumentationGeneratorToUtilEncodingServiceInterface
    {
        return new RestApiDocumentationGeneratorToUtilEncodingServiceBridge(new UtilEncodingService());
    }
}
