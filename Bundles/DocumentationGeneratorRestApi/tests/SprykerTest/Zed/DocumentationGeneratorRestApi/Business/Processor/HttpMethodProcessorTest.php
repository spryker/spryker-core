<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Processor;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AnnotationTransfer;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\DocumentationGeneratorRestApiTestFactory;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Stub\Plugin\TestResourceRoutePlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DocumentationGeneratorRestApi
 * @group Business
 * @group Processor
 * @group HttpMethodProcessorTest
 * Add your own group annotations below this line
 */
class HttpMethodProcessorTest extends Unit
{
    protected const RESOURCE_PATH = '/test-resource';
    protected const RESOURCE_ID = '{testResourceId}';
    protected const BAD_REQUEST_RESPONSE_DESCRIPTION = 'Bad Request.';
    protected const NOT_FOUND_RESPONSE_DESCRIPTION = 'Not found.';
    protected const SUMMARY = 'Test summary.';

    /**
     * @var \SprykerTest\Zed\DocumentationGeneratorRestApi\DocumentationGeneratorRestApiTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Processor\HttpMethodProcessorInterface
     */
    protected $methodProcessor;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->methodProcessor = (new DocumentationGeneratorRestApiTestFactory())->createRestApiMethodProcessor();
    }

    /**
     * @return void
     */
    public function testAddGetResourceCollectionPathWithoutAnnotationsShouldAddGetCollectionToPaths(): void
    {
        $this->methodProcessor->addGetResourceCollectionPath(
            new TestResourceRoutePlugin(),
            static::RESOURCE_PATH,
            false,
            static::RESOURCE_ID,
            null
        );

        $generatedPaths = $this->methodProcessor->getGeneratedPaths();

        $this->assertNotEmpty($generatedPaths);
        $this->assertArrayHasKey(static::RESOURCE_PATH, $generatedPaths);
        $this->assertArraySubset($this->tester->getMethodProcessorGetResourceCollectionWithoutAnnotationsExpectedData(), $generatedPaths);
    }

    /**
     * @return void
     */
    public function testAddGetResourceCollectionPathWithAnnotationsWithEmptyResponseShouldAddGetToPathsWithEmptyResponseSchema(): void
    {
        $this->methodProcessor->addGetResourceCollectionPath(
            new TestResourceRoutePlugin(),
            static::RESOURCE_PATH,
            false,
            static::RESOURCE_ID,
            (new AnnotationTransfer())->setIsEmptyResponse(true)
        );

        $generatedPaths = $this->methodProcessor->getGeneratedPaths();

        $this->assertNotEmpty($generatedPaths);
        $this->assertArrayHasKey(static::RESOURCE_PATH, $generatedPaths);
        $this->assertArraySubset($this->tester->getMethodProcessorGetResourceCollectionPathWithAnnotationsWithEmptyResponseExpectedData(), $generatedPaths);
    }

    /**
     * @return void
     */
    public function testAddGetResourceCollectionPathWithAnnotationsShouldAddGetToPathsWithDataFromAnnotations(): void
    {
        $this->methodProcessor->addGetResourceCollectionPath(
            new TestResourceRoutePlugin(),
            static::RESOURCE_PATH,
            false,
            static::RESOURCE_ID,
            (new AnnotationTransfer())->setSummary([static::SUMMARY])
        );

        $generatedPaths = $this->methodProcessor->getGeneratedPaths();

        $this->assertNotEmpty($generatedPaths);
        $this->assertArrayHasKey(static::RESOURCE_PATH, $generatedPaths);
        $this->assertArraySubset($this->tester->getMethodProcessorGetResourceCollectionPathWithAnnotationsExpectedData(), $generatedPaths);
    }

    /**
     * @return void
     */
    public function testAddDeleteResourcePathShouldAddDeleteToPaths(): void
    {
        $this->methodProcessor->addDeleteResourcePath(
            new TestResourceRoutePlugin(),
            static::RESOURCE_PATH,
            false,
            null
        );

        $generatedPaths = $this->methodProcessor->getGeneratedPaths();

        $this->assertNotEmpty($generatedPaths);
        $this->assertArrayHasKey(static::RESOURCE_PATH, $generatedPaths);
        $this->assertArraySubset($this->tester->getMethodProcessorDeleteResourcePathExpectedData(), $generatedPaths);
    }

    /**
     * @return void
     */
    public function testAddPatchResourcePathShouldAddPatchToPaths(): void
    {
        $this->methodProcessor->addPatchResourcePath(
            new TestResourceRoutePlugin(),
            static::RESOURCE_PATH,
            true,
            null
        );

        $generatedPaths = $this->methodProcessor->getGeneratedPaths();

        $this->assertNotEmpty($generatedPaths);
        $this->assertArrayHasKey(static::RESOURCE_PATH, $generatedPaths);
        $this->assertArraySubset($this->tester->getMethodProcessorPatchResourcePathExpectedData(), $generatedPaths);
    }

    /**
     * @return void
     */
    public function testAddPostResourcePathShouldAddPostToPaths(): void
    {
        $this->methodProcessor->addPostResourcePath(
            new TestResourceRoutePlugin(),
            static::RESOURCE_PATH,
            false,
            null
        );

        $generatedPaths = $this->methodProcessor->getGeneratedPaths();

        $this->assertNotEmpty($generatedPaths);
        $this->assertArrayHasKey(static::RESOURCE_PATH, $generatedPaths);
        $this->assertArraySubset($this->tester->getMethodProcessorPostResourcePathExpectedData(), $generatedPaths);
    }

    /**
     * @return void
     */
    public function testAddPostResourcePathWithAnnotationsWithEmptyResponseShouldAddPostToPathsWithEmptyResponseSchema(): void
    {
        $this->methodProcessor->addPostResourcePath(
            new TestResourceRoutePlugin(),
            static::RESOURCE_PATH,
            false,
            (new AnnotationTransfer())->setIsEmptyResponse(true)
        );

        $generatedPaths = $this->methodProcessor->getGeneratedPaths();

        $this->assertNotEmpty($generatedPaths);
        $this->assertArrayHasKey(static::RESOURCE_PATH, $generatedPaths);
        $this->assertArraySubset($this->tester->getMethodProcessorPostResourcePathWithAnnotationsWithEmptyResponseExpectedData(), $generatedPaths);
    }

    /**
     * @return void
     */
    public function testAddGetResourceByIdPathShouldAddGetResourceToPaths(): void
    {
        $this->methodProcessor->addGetResourceByIdPath(
            new TestResourceRoutePlugin(),
            static::RESOURCE_PATH,
            false,
            static::RESOURCE_ID,
            null
        );

        $generatedPaths = $this->methodProcessor->getGeneratedPaths();

        $this->assertNotEmpty($generatedPaths);
        $this->assertArrayHasKey(static::RESOURCE_PATH . '/' . static::RESOURCE_ID, $generatedPaths);
        $this->assertArraySubset($this->tester->getMethodProcessorGetResourceByIdPathExpectedData(), $generatedPaths);
    }
}
