<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferInterface;

use SprykerEngine\Zed\Transfer\Business\Model\Generator\Transfer\ClassDefinition;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\Transfer\ClassGenerator;
use SprykerEngine\Zed\Transfer\Business\Model\TransferGenerator;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @group SprykerEngine
 * @group Zed
 * @group Transfer
 * @group Business
 * @group ClassGenerator
 */
class ClassGeneratorTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->removeTargetDirectory();
    }

    public function tearDown()
    {
        $this->removeTargetDirectory();
    }

    private function removeTargetDirectory()
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->getFixtureDirectory());
    }

    /**
     * @return string
     */
    private function getFixtureDirectory()
    {
        return __DIR__ . '/Fixtures';
    }

    public function testGenerateShouldCreateTargetDirectoryIfNotExist()
    {
        $transferGenerator = new ClassGenerator($this->getFixtureDirectory());
        $transferDefinition = new ClassDefinition();
        $transferDefinition->setDefinition([
            'name' => 'Name'
        ]);
        $transferGenerator->generate($transferDefinition);

        $this->assertTrue(is_dir($this->getFixtureDirectory()));
    }
}
