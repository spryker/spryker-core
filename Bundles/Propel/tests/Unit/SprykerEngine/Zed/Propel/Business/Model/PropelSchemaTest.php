<?php

namespace Unit\SprykerEngine\Zed\Propel\Business\Model;

use SprykerEngine\Zed\Propel\Business\Model\DirectoryRemover;
use SprykerEngine\Zed\Propel\Business\Model\PropelSchema;
use SprykerEngine\Zed\Propel\Business\Model\PropelSchemaFinder;
use SprykerEngine\Zed\Propel\Business\Model\PropelSchemaReplicator;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @group SprykerEngine
 * @group Zed
 * @group Propel
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
