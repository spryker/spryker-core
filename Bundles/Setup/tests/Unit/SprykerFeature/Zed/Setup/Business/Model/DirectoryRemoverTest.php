<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Setup\Business\Model;

use SprykerFeature\Zed\Setup\Business\Model\DirectoryRemover;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Setup
 * @group Business
 * @group DirectoryRemover
 */
class DirectoryRemoverTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var string
     */
    protected $fixtureDirectory;

    public function setUp()
    {
        $this->fixtureDirectory = __DIR__ . '/Fixtures';
        $directory = $this->fixtureDirectory . DIRECTORY_SEPARATOR . 'Foo';
        mkdir($directory, 0777, true);
        $filename = $directory . DIRECTORY_SEPARATOR . 'bar';
        touch($filename);

        $this->assertFileExists($filename);
    }

    public function tearDown()
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->fixtureDirectory);
    }

    public function testAfterExecutionGeneratedDirectoryMustBeRemoved()
    {
        $directoryRemover = new DirectoryRemover($this->fixtureDirectory);
        $directoryRemover->execute();

        $this->assertFalse(is_dir($this->fixtureDirectory));
    }

}
