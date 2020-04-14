<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Twig;

use Codeception\Test\Unit;
use Spryker\Yves\Twig\TwigConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Twig
 * @group TwigConfigTest
 * Add your own group annotations below this line
 */
class TwigConfigTest extends Unit
{
    /**
     * @var \SprykerTest\Yves\Twig\TwigYvesTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetTemplatePathsShouldReturnOnlyDefaultTemplatePaths(): void
    {
        $this->tester->mockConfigMethod('getProjectNamespaces', ['Foo']);
        $twigConfig = $this->tester->getModuleConfig();

        $templatePaths = $twigConfig->getTemplatePaths();

        $this->tester->assertPathsInOrder(
            $templatePaths,
            [
                $this->tester->getDefaultPathProjectWithStore(),
                $this->tester->getDefaultPathProjectWithoutStore(),
                $this->tester->getDefaultPathProjectSharedWithStore(),
                $this->tester->getDefaultPathProjectSharedWithoutStore(),
                $this->tester->getPathSprykerShop(),
                $this->tester->getPathSprykerShopShared(),
                $this->tester->getPathSpryker(),
                $this->tester->getPathSprykerShared(),
            ]
        );
    }

    /**
     * @return void
     */
    public function testGetTemplatePathsShouldReturnOnlyDefaultTemplatePathsWhenThemeNameEqualsDefaultThemeName(): void
    {
        $this->tester->mockConfigMethod('getThemeName', 'default');
        $this->tester->mockConfigMethod('getProjectNamespaces', ['Foo']);
        $twigConfig = $this->tester->getModuleConfig();

        $templatePaths = $twigConfig->getTemplatePaths();

        $this->tester->assertPathsInOrder(
            $templatePaths,
            [
                $this->tester->getDefaultPathProjectWithStore(),
                $this->tester->getDefaultPathProjectWithoutStore(),
                $this->tester->getDefaultPathProjectSharedWithStore(),
                $this->tester->getDefaultPathProjectSharedWithoutStore(),
                $this->tester->getPathSprykerShop(),
                $this->tester->getPathSprykerShopShared(),
                $this->tester->getPathSpryker(),
                $this->tester->getPathSprykerShared(),
            ]
        );
    }

    /**
     * @return void
     */
    public function testGetTemplatePathsShouldReturnCustomAndDefaultTemplatePaths(): void
    {
        $this->tester->mockConfigMethod('getThemeName', 'custom');
        $this->tester->mockConfigMethod('getProjectNamespaces', ['Foo']);

        $twigConfig = $this->tester->getModuleConfig();

        $templatePaths = $twigConfig->getTemplatePaths();

        $this->tester->assertPathsInOrder(
            $templatePaths,
            [
                $this->tester->getCustomPathProjectWithStore(),
                $this->tester->getCustomPathProjectWithoutStore(),
                $this->tester->getCustomPathProjectSharedWithStore(),
                $this->tester->getCustomPathProjectSharedWithoutStore(),
                $this->tester->getDefaultPathProjectWithStore(),
                $this->tester->getDefaultPathProjectWithoutStore(),
                $this->tester->getDefaultPathProjectSharedWithStore(),
                $this->tester->getDefaultPathProjectSharedWithoutStore(),
                $this->tester->getPathSprykerShop(),
                $this->tester->getPathSprykerShopShared(),
                $this->tester->getPathSpryker(),
                $this->tester->getPathSprykerShared(),
            ]
        );
    }

    /**
     * @return void
     */
    public function testGetCacheFilePathReturnsString(): void
    {
        $twigConfig = new TwigConfig();
        $this->assertIsString($twigConfig->getCacheFilePath());
    }

    /**
     * @return void
     */
    public function testIsPathCacheEnabledReturnsBoolean(): void
    {
        $twigConfig = new TwigConfig();
        $this->assertIsBool($twigConfig->isPathCacheEnabled());
    }

    /**
     * @return \Spryker\Yves\Twig\TwigConfig
     */
    public function getModuleConfig(): TwigConfig
    {
        return $this->tester->getModuleConfig();
    }
}
