<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Finder;

use Codeception\Test\Unit;
use SplFileInfo;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Finder\GlueControllerFinder;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Finder\GlueControllerFinderInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToDoctrineInflectorAdapter;
use Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToFinderInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToSymfonyFinderAdapter;
use Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToTextInflectorInterface;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Stub\Plugin\TestResourceRoutePlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group DocumentationGeneratorRestApi
 * @group Business
 * @group Finder
 * @group GlueControllerFinderTest
 * Add your own group annotations below this line
 */
class GlueControllerFinderTest extends Unit
{
    protected const CONTROLLER_SOURCE_DIRECTORY = __DIR__ . '/../Stub/Controller/';
    protected const CONTROLLER_FILE_NAME = 'TestResourceController.php';

    /**
     * @return void
     */
    public function testGetGlueControllerFilesFromPluginShouldReturnArrayOfSplFileInfoObjects(): void
    {
        $controllerFinder = $this->createGlueControllerFinder();

        $files = $controllerFinder->getGlueControllerFilesFromPlugin($this->createTestResourceRoutePlugin());

        $this->assertInternalType('array', $files);
        $this->assertNotEmpty($files);
        foreach ($files as $file) {
            $this->assertInstanceOf(SplFileInfo::class, $file);
        }
    }

    /**
     * @return void
     */
    public function testGetGlueControllerFilesFromPluginShouldReturnCorrectControllerFile(): void
    {
        $controllerFinder = $this->createGlueControllerFinder();

        $files = $controllerFinder->getGlueControllerFilesFromPlugin($this->createTestResourceRoutePlugin());

        foreach ($files as $file) {
            $this->assertEquals(static::CONTROLLER_FILE_NAME, $file->getFilename());
        }
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Business\Finder\GlueControllerFinder
     */
    protected function createGlueControllerFinder(): GlueControllerFinderInterface
    {
        return new GlueControllerFinder(
            $this->createDocumentationGeneratorRestApiToFinder(),
            $this->createDocumentationGeneratorRestApiToTextInflector(),
            [
                static::CONTROLLER_SOURCE_DIRECTORY,
            ]
        );
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToFinderInterface
     */
    protected function createDocumentationGeneratorRestApiToFinder(): DocumentationGeneratorRestApiToFinderInterface
    {
        return new DocumentationGeneratorRestApiToSymfonyFinderAdapter();
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToTextInflectorInterface
     */
    protected function createDocumentationGeneratorRestApiToTextInflector(): DocumentationGeneratorRestApiToTextInflectorInterface
    {
        return new DocumentationGeneratorRestApiToDoctrineInflectorAdapter();
    }

    /**
     * @return \SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Stub\Plugin\TestResourceRoutePlugin
     */
    protected function createTestResourceRoutePlugin(): TestResourceRoutePlugin
    {
        return new TestResourceRoutePlugin();
    }
}
