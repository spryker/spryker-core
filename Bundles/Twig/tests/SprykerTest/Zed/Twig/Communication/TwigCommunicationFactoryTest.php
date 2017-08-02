<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Twig\Communication;

use PHPUnit_Framework_TestCase;
use Spryker\Zed\Twig\Communication\TwigCommunicationFactory;
use Twig_LoaderInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Twig
 * @group Communication
 * @group TwigCommunicationFactoryTest
 * Add your own group annotations below this line
 */
class TwigCommunicationFactoryTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testCreateFilesystemLoaderReturnsTwigLoader()
    {
        $twigCommunicationFactory = new TwigCommunicationFactory();
        $filesystemLoader = $twigCommunicationFactory->createFilesystemLoader();

        $this->assertInstanceOf(Twig_LoaderInterface::class, $filesystemLoader);
    }

}
