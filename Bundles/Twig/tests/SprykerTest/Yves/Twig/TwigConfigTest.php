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
    public function testGetTemplatePathsShouldReturnAnArrayWithOnlyDefaultTemplatePaths()
    {
        $twigConfig = $this->tester->getModuleConfig();

        $templatePaths = $twigConfig->getTemplatePaths();
        $this->assertCount(4, $templatePaths);
    }

    /**
     * @return void
     */
    public function testGetTemplatePathsShouldReturnAnArrayShouldReturnCustomAndDefaultTemplatePaths()
    {
        $this->tester->mockConfigMethod('getThemeNames', ['custom', 'default']);
        $twigConfig = $this->tester->getModuleConfig();

        $templatePaths = $twigConfig->getTemplatePaths();
        $this->assertCount(8, $templatePaths);
    }

    /**
     * @return void
     */
    public function testGetCacheFilePathReturnsString()
    {
        $twigConfig = new TwigConfig();
        $this->assertIsString($twigConfig->getCacheFilePath());
    }

    /**
     * @return void
     */
    public function testIsPathCacheEnabledReturnsBoolean()
    {
        $twigConfig = new TwigConfig();
        $this->assertIsBool($twigConfig->isPathCacheEnabled());
    }
}
