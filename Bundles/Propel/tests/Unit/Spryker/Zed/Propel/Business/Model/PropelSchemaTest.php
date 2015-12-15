<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Propel\Business\Model;

use Spryker\Zed\Propel\Business\Model\PropelSchema;
use Spryker\Zed\Propel\Business\Model\PropelSchemaMerger;
use Spryker\Zed\Propel\Business\Model\PropelGroupedSchemaFinder;
use Spryker\Zed\Propel\Business\Model\PropelSchemaFinder;
use Spryker\Zed\Propel\Business\Model\PropelSchemaWriter;
use Symfony\Component\Filesystem\Filesystem;

class PropelSchemaTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function tearDown()
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->getFixtureTargetDirectory());
    }

    /**
     * @return string
     */
    private function getFixtureDirectory()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . 'PropelSchema';
    }

    /**
     * @return string
     */
    private function getFixtureTargetDirectory()
    {
        return $this->getFixtureDirectory() . DIRECTORY_SEPARATOR . 'Target';
    }

    /**
     * @return void
     */
    public function testCopyShouldCopyFileFromSourceToTargetDirectoryWithoutMergingIfOnlyOneFileBySchemaNameExist()
    {
        $finder = new PropelSchemaFinder([$this->getFixtureDirectory()]);
        $groupedFinder = new PropelGroupedSchemaFinder($finder);
        $writer = new PropelSchemaWriter(new Filesystem(), $this->getFixtureTargetDirectory());
        $merger = new PropelSchemaMerger();

        $this->assertFalse(file_exists($this->getFixtureTargetDirectory() . DIRECTORY_SEPARATOR . 'foo_foo.schema.xml'));
        $schema = new PropelSchema($groupedFinder, $writer, $merger);
        $schema->copy();

        $this->assertTrue(file_exists($this->getFixtureTargetDirectory() . DIRECTORY_SEPARATOR . 'foo_foo.schema.xml'));
    }

    /**
     * @return void
     */
    public function testCopyShouldMergeAndCopyFileFromSourceToTargetDirectoryIfMoreThenOneFileBySchemaNameExist()
    {
        $finder = new PropelSchemaFinder([
            $this->getFixtureDirectory() . DIRECTORY_SEPARATOR . 'Project',
            $this->getFixtureDirectory() . DIRECTORY_SEPARATOR . 'Vendor',
        ]);
        $groupedFinder = new PropelGroupedSchemaFinder($finder);
        $writer = new PropelSchemaWriter(new Filesystem(), $this->getFixtureTargetDirectory());
        $merger = new PropelSchemaMerger();

        $this->assertFalse(file_exists($this->getFixtureTargetDirectory() . DIRECTORY_SEPARATOR . 'foo_bar.schema.xml'));
        $schema = new PropelSchema($groupedFinder, $writer, $merger);
        $schema->copy();

        $this->assertTrue(file_exists($this->getFixtureTargetDirectory() . DIRECTORY_SEPARATOR . 'foo_bar.schema.xml'));
    }

}
