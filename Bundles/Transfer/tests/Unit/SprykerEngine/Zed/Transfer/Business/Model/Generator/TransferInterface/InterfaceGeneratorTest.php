<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferInterface;

use SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferInterface\InterfaceDefinition;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferInterface\InterfaceGenerator;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @group SprykerEngine
 * @group Zed
 * @group Transfer
 * @group Business
 * @group InterfaceGenerator
 */
class InterfaceGeneratorTest extends \PHPUnit_Framework_TestCase
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
        $interfaceGenerator = new InterfaceGenerator($this->getFixtureDirectory());
        $interfaceDefinition = new InterfaceDefinition();
        $interfaceDefinition->setDefinition([
            'bundle' => 'Bundle',
            'name' => 'Name',
        ]);
        $interfaceGenerator->generate($interfaceDefinition);

        $this->assertTrue(is_dir($this->getFixtureDirectory() . DIRECTORY_SEPARATOR . 'Bundle'));
    }
}
