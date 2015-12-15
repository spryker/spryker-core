<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Propel\Business\Model;

use Symfony\Component\Filesystem\Filesystem;

abstract class AbstractPropelSchemaTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        mkdir($this->getFixtureDirectory());
        touch($this->getFixtureDirectory() . DIRECTORY_SEPARATOR . 'spy_foo.schema.xml');
        touch($this->getFixtureDirectory() . DIRECTORY_SEPARATOR . 'spy_bar.schema.xml');
    }

    /**
     * @return void
     */
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
        return __DIR__ . '/TempFixtures';
    }

}
