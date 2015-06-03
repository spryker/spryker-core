<?php

namespace Unit\SprykerEngine\Zed\Propel\Business\Model;

use SprykerEngine\Zed\Propel\Business\Model\PropelSchemaFinder;
use SprykerEngine\Zed\Propel\Business\Model\PropelSchemaReplicator;

/**
 * @group SprykerEngine
 * @group Zed
 * @group Propel
 * @group Business
 * @group PropelSchemaReplicator
 */
class PropelSchemaReplicatorTest extends AbstractPropelSchemaTest
{

    public function testReplicateSchemaFilesShouldCopyFilesToTargetDirectory()
    {
        $schemaFinder = new PropelSchemaFinder(
            [$this->getFixtureDirectory()],
            '*.schema.xml'
        );

        $schemaReplicator = new PropelSchemaReplicator($this->getFixtureDirectory() . '/target/');
        $schemaReplicator->replicateSchemaFiles($schemaFinder);

        $this->assertTrue(file_exists($this->getTargetDirectory() . DIRECTORY_SEPARATOR . 'foo.schema.xml'));
        $this->assertTrue(file_exists($this->getTargetDirectory() . DIRECTORY_SEPARATOR . 'bar.schema.xml'));
    }

    /**
     * @return string
     */
    private function getTargetDirectory()
    {
        return $this->getFixtureDirectory() . DIRECTORY_SEPARATOR . '/target';
    }

}
