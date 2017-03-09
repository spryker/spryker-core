<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Yves\Twig\Model\Loader;

use PHPUnit_Framework_TestCase;
use Spryker\Yves\Twig\Model\Loader\FilesystemLoader;
use Twig_LoaderInterface;

/**
 * @group Unit
 * @group Spryker
 * @group Yves
 * @group Twig
 * @group Model
 * @group Loader
 * @group FilesystemLoaderTest
 */
class FilesystemLoaderTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testCanBeConstructedWithoutTemplatePathsArray()
    {
        $filesystemLoader = new FilesystemLoader();

        $this->assertInstanceOf(Twig_LoaderInterface::class, $filesystemLoader);
    }

    /**
     * @return void
     */
    public function testCanBeConstrictedWithTemplatePathsArray()
    {
        $templatePaths = [];
        $filesystemLoader = new FilesystemLoader($templatePaths);

        $this->assertInstanceOf(Twig_LoaderInterface::class, $filesystemLoader);
    }

}
