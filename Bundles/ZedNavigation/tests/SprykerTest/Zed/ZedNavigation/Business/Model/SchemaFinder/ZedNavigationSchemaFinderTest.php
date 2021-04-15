<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ZedNavigation\Business\Model\SchemaFinder;

use Spryker\Zed\ZedNavigation\Business\Model\SchemaFinder\ZedNavigationSchemaFinder;
use SprykerTest\Zed\ZedNavigation\Business\ZedNavigationBusinessTester;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ZedNavigation
 * @group Business
 * @group Model
 * @group SchemaFinder
 * @group ZedNavigationSchemaFinderTest
 * Add your own group annotations below this line
 */
class ZedNavigationSchemaFinderTest extends ZedNavigationBusinessTester
{
    /**
     * @return void
     */
    public function setUp(): void
    {
        mkdir($this->getFixtureDirectory());
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        $fileSystem = new Filesystem();
        $fileSystem->remove($this->getFixtureDirectory());
    }

    /**
     * @return string
     */
    private function getFixtureDirectory(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'Fixtures';
    }

    /**
     * @return void
     */
    public function testGetSchemasShouldReturnIterateableFileCollection(): void
    {
        $schemaFinder = new ZedNavigationSchemaFinder([$this->getFixtureDirectory()]);

        $this->assertInstanceOf(Finder::class, $schemaFinder->getSchemaFiles('navigation.xml'));
    }
}
