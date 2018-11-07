<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Analyzer;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RestTokenResponseAttributesTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\GlueAnnotationAnalyzer;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Exception\InvalidAnnotationFormatException;
use Spryker\Zed\DocumentationGeneratorRestApi\Dependency\Service\DocumentationGeneratorRestApiToUtilEncodingServiceInterface;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\DocumentationGeneratorRestApiTestFactory;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Stub\Plugin\TestResourceRoutePlugin;

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
    protected const CONTROLLER_SOURCE_DIRECTORY = __DIR__ . '/../Stub/Controller/';
    protected const CONTROLLER_FILE_NAME = 'TestResourceController.php';
    protected const CONTROLLER_WITH_INVALID_ANNOTATIONS_FILE_NAME = 'TestResourceWithInvalidAnnotationsController.php';
    protected const CONTROLLER_WITHOUT_ANNOTATIONS = 'TestResourceWithoutAnnotationsController.php';
    protected const CONTROLLER_WITH_EMPTY_ANNOTATIONS = 'TestResourceWithEmptyAnnotationsController.php';

    protected const SUMMARY = 'Summary example';
    protected const RESPONSE_CLASS = RestTokenResponseAttributesTransfer::class;
    protected const HEADER_ACCEPT_LANGUAGE = 'Accept-Language';
    protected const KEY_RESPONSE_BAD_REQUEST = 400;
    protected const KEY_RESPONSE_NOT_FOUND = 404;
    protected const KEY_RESPONSE_SERVER_ERROR = 500;
    protected const VALUE_RESPONSE_BAD_REQUEST = 'Bad Request';
    protected const VALUE_RESPONSE_NOT_FOUND = 'Item not found';
    protected const VALUE_RESPONSE_SERVER_ERROR = 'Server Error';

    /**
     * @var \SprykerTest\Zed\DocumentationGeneratorRestApi\Business\DocumentationGeneratorRestApiTestFactory
     */
    protected $testFactory;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->testFactory = new DocumentationGeneratorRestApiTestFactory();
    }

    /**
     * @return void
     */
    public function testGetResourceParametersFromPluginWillReturnCorrectParameters(): void
    {
        $glueAnnotationAnalyzer = new GlueAnnotationAnalyzer(
            $this->getGlueControllerFinder(static::CONTROLLER_SOURCE_DIRECTORY . DIRECTORY_SEPARATOR . static::CONTROLLER_FILE_NAME),
            $this->getUtilEncodingService()
        );
        $parameters = $glueAnnotationAnalyzer->getResourceParametersFromPlugin(new TestResourceRoutePlugin());

        $this->assertNotEmpty($parameters->getGetResource());
        $this->assertNotEmpty($parameters->getPost());
        $this->assertEmpty($parameters->getPatch());
        $this->assertEmpty($parameters->getGetCollection());
        $this->assertEmpty($parameters->getGetResourceById());
        $this->assertEmpty($parameters->getDelete());
        $this->assertEquals([static::SUMMARY], $parameters->getGetResource()->getSummary());
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
     * @return void
     */
    public function testGetResourceParametersFromPluginWillThrowExceptionIfAnnotationsContainsInvalidJson(): void
    {
        $glueAnnotationAnalyzer = new GlueAnnotationAnalyzer(
            $this->getGlueControllerFinder(static::CONTROLLER_SOURCE_DIRECTORY . DIRECTORY_SEPARATOR . static::CONTROLLER_WITH_INVALID_ANNOTATIONS_FILE_NAME),
            $this->getUtilEncodingService()
        );

        $this->expectException(InvalidAnnotationFormatException::class);
        $glueAnnotationAnalyzer->getResourceParametersFromPlugin(new TestResourceRoutePlugin());
    }

    /**
     * @return void
     */
    public function testGetResourceParametersFromPluginWillReturnWithEmptyPropertiesIfAnnotationsIsNotFoundInController(): void
    {
        $glueAnnotationAnalyzer = new GlueAnnotationAnalyzer(
            $this->getGlueControllerFinder(static::CONTROLLER_SOURCE_DIRECTORY . DIRECTORY_SEPARATOR . static::CONTROLLER_WITHOUT_ANNOTATIONS),
            $this->getUtilEncodingService()
        );

        $parameters = $glueAnnotationAnalyzer->getResourceParametersFromPlugin(new TestResourceRoutePlugin());

        $this->assertEmpty($parameters->getGetResource());
        $this->assertEmpty($parameters->getPost());
        $this->assertEmpty($parameters->getPatch());
        $this->assertEmpty($parameters->getGetCollection());
        $this->assertEmpty($parameters->getGetResourceById());
        $this->assertEmpty($parameters->getDelete());
    }

    /**
     * @return void
     */
    public function testGetResourceParametersFromPluginWillReturnObjectWithEmptyPropertiesIfAnnotationsIsAnEmptyObject(): void
    {
        $glueAnnotationAnalyzer = new GlueAnnotationAnalyzer(
            $this->getGlueControllerFinder(static::CONTROLLER_SOURCE_DIRECTORY . DIRECTORY_SEPARATOR . static::CONTROLLER_WITH_EMPTY_ANNOTATIONS),
            $this->getUtilEncodingService()
        );

        $parameters = $glueAnnotationAnalyzer->getResourceParametersFromPlugin(new TestResourceRoutePlugin());

        $this->assertEmpty($parameters->getGetResource());
        $this->assertEmpty($parameters->getPost());
        $this->assertEmpty($parameters->getPatch());
        $this->assertEmpty($parameters->getGetCollection());
        $this->assertEmpty($parameters->getGetResourceById());
        $this->assertEmpty($parameters->getDelete());
    }

    /**
     * @param string $controller
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\DocumentationGeneratorRestApi\Business\Finder\GlueControllerFinderInterface
     */
    protected function getGlueControllerFinder(string $controller): MockObject
    {
        return $this->testFactory->createGlueControllerFinderMock($controller);
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Dependency\Service\DocumentationGeneratorRestApiToUtilEncodingServiceInterface
     */
    protected function getUtilEncodingService(): DocumentationGeneratorRestApiToUtilEncodingServiceInterface
    {
        return $this->testFactory->createUtilEncodingService();
    }
}
