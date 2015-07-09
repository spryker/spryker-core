<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Application\Business\Model\Navigation\SchemaFinder;

use SprykerFeature\Zed\Application\Business\Model\Navigation\SchemaFinder\NavigationSchemaFinder;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Application
 * @group Business
 * @group NavigationSchemaFinder
 */
class NavigationSchemaFinderTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        mkdir($this->getFixtureDirectory());
    }

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

    public function testGetSchemasShouldReturnIterateableFileCollection()
    {
        $schemaFinder = new NavigationSchemaFinder(
            [$this->getFixtureDirectory()],
            'file name pattern'
        );

        $this->assertInstanceOf('Symfony\Component\Finder\Finder', $schemaFinder->getSchemaFiles());
    }

}
