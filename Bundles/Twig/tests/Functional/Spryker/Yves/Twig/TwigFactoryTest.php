<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Yves\Twig;

use PHPUnit_Framework_TestCase;
use Spryker\Yves\Twig\TwigFactory;
use Twig_LoaderInterface;

/**
 * @group Functional
 * @group Spryker
 * @group Yves
 * @group Twig
 * @group TwigFactoryTest
 */
class TwigFactoryTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testCreateFilesystemLoaderReturnsLoaderInterface()
    {
        $twigFactory = new TwigFactory();
        $filesystemLoader = $twigFactory->createFilesystemLoader();

        $this->assertInstanceOf(Twig_LoaderInterface::class, $filesystemLoader);
    }

}
