<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Twig;

use PHPUnit_Framework_TestCase;
use Spryker\Zed\Twig\TwigConfig;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Twig
 * @group TwigConfigTest
 */
class TwigConfigTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testGetBundlesDirectoryReturnsAString()
    {
        $twigConfig = new TwigConfig();
        $this->assertInternalType('string', $twigConfig->getBundlesDirectory());
    }

    /**
     * @return void
     */
    public function testGetTemplatePathsReturnsAnArray()
    {
        $twigConfig = new TwigConfig();
        $this->assertInternalType('array', $twigConfig->getTemplatePaths());
    }

    /**
     * @return void
     */
    public function testGetCacheFilePathReturnsString()
    {
        $twigConfig = new TwigConfig();
        $this->assertInternalType('string', $twigConfig->getCacheFilePath());
    }

    /**
     * @return void
     */
    public function testGetCacheFilePathForYvesReturnsString()
    {
        $twigConfig = new TwigConfig();
        $this->assertInternalType('string', $twigConfig->getCacheFilePathForYves());
    }

    /**
     * @return void
     */
    public function testIsPathCacheEnabledReturnsBoolean()
    {
        $twigConfig = new TwigConfig();
        $this->assertInternalType('bool', $twigConfig->isPathCacheEnabled());
    }

    /**
     * @return void
     */
    public function testGetZedDirectoryPathPattern()
    {
        $twigConfig = new TwigConfig();
        $this->assertInternalType('array', $twigConfig->getZedDirectoryPathPatterns());
    }

    /**
     * @return void
     */
    public function testGetYvesDirectoryPathPattern()
    {
        $twigConfig = new TwigConfig();
        $this->assertInternalType('array', $twigConfig->getYvesDirectoryPathPatterns());
    }

}
