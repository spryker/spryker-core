<?php

namespace Unit\SprykerFeature\Zed\Setup\Business\Model\Propel;

use SprykerFeature\Zed\Setup\Business\Model\DirectoryRemover;
use SprykerFeature\Zed\Setup\Business\Model\Propel\PropelSchema;
use SprykerFeature\Zed\Setup\Business\Model\Propel\PropelSchemaFinder;
use SprykerFeature\Zed\Setup\Business\Model\Propel\PropelSchemaReplicator;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Setup
 * @group Business
 * @group PropelSchema
 */
class PropelSchemaTest extends AbstractPropelSchemaTest
{

    public function testCleanTargetDirectoryShouldRemoveTargetDirectoryAndAllFilesInIt()
    {
        $propelSchema = $this->getPropelSchemaModel();

        $this->assertTrue(file_exists($this->getFixtureDirectory() . DIRECTORY_SEPARATOR . 'spy_foo.schema.xml'));

        $propelSchema->cleanTargetDirectory();

        $this->assertFalse(is_dir($this->getFixtureDirectory()));
    }

    public function testCopySchemasShouldCollectAllSchemaFilesAndCopyThemToSchemaDirectory()
    {
        $propelSchema = $this->getPropelSchemaModel();
        $propelSchema->copyToTargetDirectory();

        $this->assertTrue(file_exists($this->getTargetDirectory() . DIRECTORY_SEPARATOR . 'spy_foo.schema.xml'));
        $this->assertTrue(file_exists($this->getTargetDirectory() . DIRECTORY_SEPARATOR . 'spy_bar.schema.xml'));
    }

    /**
     * @return PropelSchema
     */
    private function getPropelSchemaModel()
    {
        $propelSchema = new PropelSchema(
            new DirectoryRemover($this->getFixtureDirectory()),
            new PropelSchemaFinder([$this->getFixtureDirectory()], '*.schema.xml'),
            new PropelSchemaReplicator($this->getTargetDirectory())
        );

        return $propelSchema;
    }

    /**
     * @return string
     */
    private function getTargetDirectory()
    {
        return $this->getFixtureDirectory() . DIRECTORY_SEPARATOR . '/target';
    }

}
