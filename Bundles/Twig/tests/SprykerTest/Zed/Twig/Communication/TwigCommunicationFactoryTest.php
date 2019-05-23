<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Twig\Communication;

use Codeception\Test\Unit;
use Spryker\Zed\Twig\Communication\TwigCommunicationFactory;
use Twig\Loader\LoaderInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Twig
 * @group Communication
 * @group TwigCommunicationFactoryTest
 * Add your own group annotations below this line
 */
class TwigCommunicationFactoryTest extends Unit
{
    /**
     * @return void
     */
    public function testCreateFilesystemLoaderReturnsTwigLoader()
    {
        $twigCommunicationFactory = new TwigCommunicationFactory();
        $filesystemLoader = $twigCommunicationFactory->createFilesystemLoader();

        $this->assertInstanceOf(LoaderInterface::class, $filesystemLoader);
    }
}
