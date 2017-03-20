<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\ZedNavigation\Business\Model\SchemaFinder;

use PHPUnit_Framework_TestCase;
use Spryker\Zed\ZedNavigation\Business\Model\SchemaFinder\ZedNavigationSchemaFinder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group ZedNavigation
 * @group Business
 * @group Model
 * @group SchemaFinder
 * @group ZedNavigationSchemaFinderTest
 */
class ZedNavigationSchemaFinderTest extends PHPUnit_Framework_TestCase
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
        $schemaFinder = new ZedNavigationSchemaFinder(
            [$this->getFixtureDirectory()],
            'file name pattern'
        );

        $this->assertInstanceOf(Finder::class, $schemaFinder->getSchemaFiles());
    }

}
