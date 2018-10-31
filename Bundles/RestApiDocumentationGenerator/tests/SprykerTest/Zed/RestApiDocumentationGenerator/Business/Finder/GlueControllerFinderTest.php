<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RestApiDocumentationGenerator\Business\Finder;

use Codeception\Test\Unit;
use SplFileInfo;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Finder\GlueControllerFinder;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Finder\GlueControllerFinderInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToDoctrineInflectorAdapter;
use Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToFinderInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToSymfonyFinderAdapter;
use Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToTextInflectorInterface;
use SprykerTest\Zed\RestApiDocumentationGenerator\Business\Stub\Plugin\TestResourceRoutePlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group RestApiDocumentationGenerator
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
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Finder\GlueControllerFinder
     */
    protected function createGlueControllerFinder(): GlueControllerFinderInterface
    {
        return new GlueControllerFinder(
            $this->createRestApiDocumentationGeneratorToFinder(),
            $this->createRestApiDocumentationGeneratorToTextInflector(),
            [
                static::CONTROLLER_SOURCE_DIRECTORY,
            ]
        );
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToFinderInterface
     */
    protected function createRestApiDocumentationGeneratorToFinder(): RestApiDocumentationGeneratorToFinderInterface
    {
        return new RestApiDocumentationGeneratorToSymfonyFinderAdapter();
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToTextInflectorInterface
     */
    protected function createRestApiDocumentationGeneratorToTextInflector(): RestApiDocumentationGeneratorToTextInflectorInterface
    {
        return new RestApiDocumentationGeneratorToDoctrineInflectorAdapter();
    }

    /**
     * @return \SprykerTest\Zed\RestApiDocumentationGenerator\Business\Stub\Plugin\TestResourceRoutePlugin
     */
    protected function createTestResourceRoutePlugin(): TestResourceRoutePlugin
    {
        return new TestResourceRoutePlugin();
    }
}
