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
     * @return void
     */
    public function testGetTemplatePathsShouldReturnAnArray()
    {
        $twigConfig = new TwigConfig();

        $this->assertIsArray($twigConfig->getTemplatePaths());
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
