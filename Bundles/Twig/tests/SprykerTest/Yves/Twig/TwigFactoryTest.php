<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Twig;

use Codeception\Test\Unit;
use Spryker\Yves\Twig\TwigFactory;
use Twig\Loader\LoaderInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Yves
 * @group Twig
 * @group TwigFactoryTest
 * Add your own group annotations below this line
 */
class TwigFactoryTest extends Unit
{
    /**
     * @return void
     */
    public function testCreateFilesystemLoaderReturnsLoaderInterface()
    {
        $twigFactory = new TwigFactory();
        $filesystemLoader = $twigFactory->createFilesystemLoader();

        $this->assertInstanceOf(LoaderInterface::class, $filesystemLoader);
    }
}
