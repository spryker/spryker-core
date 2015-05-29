<?php

namespace Unit\SprykerFeature\Zed\Setup\Business\Model\Propel;

use Symfony\Component\Filesystem\Filesystem;

abstract class AbstractPropelSchemaTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        mkdir($this->getFixtureDirectory());
        touch($this->getFixtureDirectory() . DIRECTORY_SEPARATOR . 'foo.schema.xml');
        touch($this->getFixtureDirectory() . DIRECTORY_SEPARATOR . 'bar.schema.xml');
    }

    public function tearDown()
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->getFixtureDirectory());
    }

    /**
     * @return string
     */
    protected function getFixtureDirectory()
    {
        return __DIR__ . '/Fixtures';
    }
}
