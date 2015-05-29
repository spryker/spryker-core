<?php

namespace Unit\SprykerFeature\Zed\Setup\Business\Model\Propel;
use SprykerFeature\Zed\Setup\Business\Model\Propel\PropelSchemaFinder;
use SprykerFeature\Zed\Setup\Business\Model\Propel\PropelSchemaReplicator;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Setup
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
