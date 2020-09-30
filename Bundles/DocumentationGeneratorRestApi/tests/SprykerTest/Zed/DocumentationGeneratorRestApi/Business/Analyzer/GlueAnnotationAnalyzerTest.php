<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Analyzer;

use Codeception\Test\Unit;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\GlueAnnotationAnalyzer;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Exception\InvalidAnnotationFormatException;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Finder\GlueControllerFinderInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Dependency\Service\DocumentationGeneratorRestApiToUtilEncodingServiceInterface;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\DocumentationGeneratorRestApiTestFactory;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Stub\Plugin\TestResourceRoutePlugin;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Stub\RestTestAlternativeAttributesTransfer;

/**
 * Auto-generated group annotations
 *
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
    protected const CONTROLLER_WITH_NULLABLE_ID = 'TestResourceWithNullableIdController.php';

    protected const SUMMARY = 'Summary example';
    protected const ALTERNATIVE_RESPONSE_CLASS = RestTestAlternativeAttributesTransfer::class;
    protected const HEADER_ACCEPT_LANGUAGE = 'Accept-Language';
    protected const HEADER_ACCEPT_LANGUAGE_REF = 'acceptLanguage';
    protected const KEY_RESPONSE_BAD_REQUEST = 400;
    protected const KEY_RESPONSE_NOT_FOUND = 404;
    protected const VALUE_RESPONSE_BAD_REQUEST = 'Bad Request';
    protected const VALUE_RESPONSE_NOT_FOUND = 'Item not found';

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

        $this->assertNotEmpty($parameters->getGetResourceById());
        $this->assertNotEmpty($parameters->getPost());
        $this->assertEmpty($parameters->getPatch());
        $this->assertEmpty($parameters->getGetCollection());
        $this->assertEmpty($parameters->getDelete());
        $this->assertEquals([static::SUMMARY], $parameters->getGetResourceById()->getSummary());
        $this->assertNotEmpty($parameters->getGetResourceById()->getParameters());
        $this->assertSame(static::HEADER_ACCEPT_LANGUAGE_REF, $parameters->getGetResourceById()->getParameters()[0]->getRef());
        $this->assertNotEmpty($parameters->getGetResourceById()->getResponses());
        $this->assertArrayHasKey(static::KEY_RESPONSE_BAD_REQUEST, $parameters->getGetResourceById()->getResponses());
        $this->assertArrayHasKey(static::KEY_RESPONSE_NOT_FOUND, $parameters->getGetResourceById()->getResponses());
        $this->assertSame(static::VALUE_RESPONSE_BAD_REQUEST, $parameters->getGetResourceById()->getResponses()[static::KEY_RESPONSE_BAD_REQUEST]);
        $this->assertSame(static::VALUE_RESPONSE_NOT_FOUND, $parameters->getGetResourceById()->getResponses()[static::KEY_RESPONSE_NOT_FOUND]);
        $this->assertNotEmpty($parameters->getPost()->getResponseAttributesClassName());
        $this->assertSame(static::ALTERNATIVE_RESPONSE_CLASS, $parameters->getPost()->getResponseAttributesClassName());
        $this->assertArrayHasKey(static::KEY_RESPONSE_BAD_REQUEST, $parameters->getPost()->getResponses());
        $this->assertSame(static::VALUE_RESPONSE_BAD_REQUEST, $parameters->getPost()->getResponses()[static::KEY_RESPONSE_BAD_REQUEST]);
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

        $this->assertEmpty($parameters->getPost());
        $this->assertEmpty($parameters->getPatch());
        $this->assertEmpty($parameters->getGetCollection());
        $this->assertEmpty($parameters->getGetResourceById());
        $this->assertEmpty($parameters->getDelete());
    }

    /**
     * @return void
     */
    public function testGetResourceParametersFromPluginWithNullableIdWillReturnCorrectParameters(): void
    {
        $glueAnnotationAnalyzer = new GlueAnnotationAnalyzer(
            $this->getGlueControllerFinder(static::CONTROLLER_SOURCE_DIRECTORY . DIRECTORY_SEPARATOR . static::CONTROLLER_WITH_NULLABLE_ID),
            $this->getUtilEncodingService()
        );

        $parameters = $glueAnnotationAnalyzer->getResourceParametersFromPlugin(new TestResourceRoutePlugin());
        $this->assertNotEmpty($parameters->getGetCollection());
        $this->assertTrue($parameters->getGetCollection()->getIsIdNullable());
    }

    /**
     * @param string $controller
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\DocumentationGeneratorRestApi\Business\Finder\GlueControllerFinderInterface
     */
    protected function getGlueControllerFinder(string $controller): GlueControllerFinderInterface
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
