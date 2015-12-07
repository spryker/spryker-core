<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Maintenance\Business\InstalledPackages;

use SprykerFeature\Zed\Maintenance\Business\InstalledPackages\MarkDownWriter;
use Generated\Shared\Transfer\InstalledPackagesTransfer;
use Generated\Shared\Transfer\InstalledPackageTransfer;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Maintenance
 * @group Business
 * @group MarkDownWriter
 */
class MarkDownWriterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->cleanUpFixtureDirectory();
        mkdir($this->getFixtureDirectory());
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();
        $this->cleanUpFixtureDirectory();
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
    public function testCallWriteShouldCreateMarkDownFile()
    {
        $collection = new InstalledPackagesTransfer();
        $installedPackage = new InstalledPackageTransfer();
        $installedPackage->setName('Foo');
        $installedPackage->setType('Bar');
        $installedPackage->setVersion(1);
        $installedPackage->setLicense('MIT');
        $installedPackage->setUrl('url to somewhere');

        $collection->addPackage($installedPackage);

        $markDownWriter = new MarkDownWriter($collection, $this->getFixtureDirectory() . DIRECTORY_SEPARATOR . 'Foss.md');
        $markDownWriter->write();

        $this->assertFileExists($this->getFixtureDirectory() . DIRECTORY_SEPARATOR . 'Foss.md');
    }

    /**
     * @return void
     */
    private function cleanUpFixtureDirectory()
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->getFixtureDirectory());
    }

}
