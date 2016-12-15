<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Navigation\Business\Model\SchemaFinder;

use PHPUnit_Framework_TestCase;
use Spryker\Zed\Navigation\Business\Model\SchemaFinder\NavigationSchemaFinder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Navigation
 * @group Business
 * @group Model
 * @group SchemaFinder
 * @group NavigationSchemaFinderTest
 */
class NavigationSchemaFinderTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function setUp()
    {
        mkdir($this->getFixtureDirectory());
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        $fileSystem = new Filesystem();
        $fileSystem->remove($this->getFixtureDirectory());
    }

    /**
     * @return string
     */
    private function getFixtureDirectory()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'Fixtures';
    }

    /**
     * @return void
     */
    public function testGetSchemasShouldReturnIterateableFileCollection()
    {
        $schemaFinder = new NavigationSchemaFinder(
            [$this->getFixtureDirectory()],
            'file name pattern'
        );

        $this->assertInstanceOf(Finder::class, $schemaFinder->getSchemaFiles());
    }

}
