<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Propel\Business\Model;

use SprykerEngine\Zed\Propel\Business\Model\PropelGroupedSchemaFinder;
use SprykerEngine\Zed\Propel\Business\Model\PropelSchemaFinder;

/**
 * @group SprykerEngine
 * @group Zed
 * @group Propel
 * @group Business
 * @group PropelGroupedSchemaFinder
 */
class PropelGroupedSchemaFinderTest extends AbstractPropelSchemaTest
{

    const NAME_OF_SCHEMA_FILE_GROUP = 'spy_foo.schema.xml';

    public function testGetSchemasShouldReturnArrayWithOneEntryGroupedByFileNameIfFileWithSameNameOnlyExistsOnce()
    {
        $schemaFinder = new PropelSchemaFinder(
            [$this->getFixtureDirectory()]
        );

        $schemaGrouper = new PropelGroupedSchemaFinder(
            $schemaFinder
        );

        $groupedSchemaFiles = $schemaGrouper->getGroupedSchemaFiles();
        $this->assertInternalType('array', $groupedSchemaFiles);
        $this->assertArrayHasKey(self::NAME_OF_SCHEMA_FILE_GROUP, $groupedSchemaFiles);
        $this->assertCount(1, $groupedSchemaFiles[self::NAME_OF_SCHEMA_FILE_GROUP]);
    }

    public function testGetSchemasShouldReturnArrayWithTwoEntriesGroupedByFileNameIfFileWithSameNameExistsMoreThenOnce()
    {
        $subDirectory = $this->getFixtureDirectory() . DIRECTORY_SEPARATOR . 'subDir';
        if (!is_dir($subDirectory)) {
            mkdir($subDirectory);
        }
        touch($subDirectory . DIRECTORY_SEPARATOR . self::NAME_OF_SCHEMA_FILE_GROUP);

        $schemaFinder = new PropelSchemaFinder(
            [$this->getFixtureDirectory(), $subDirectory]
        );

        $schemaGrouper = new PropelGroupedSchemaFinder(
            $schemaFinder
        );

        $groupedSchemaFiles = $schemaGrouper->getGroupedSchemaFiles();
        $this->assertInternalType('array', $groupedSchemaFiles);
        $this->assertArrayHasKey(self::NAME_OF_SCHEMA_FILE_GROUP, $groupedSchemaFiles);
        $this->assertCount(2, $groupedSchemaFiles[self::NAME_OF_SCHEMA_FILE_GROUP]);
    }

}
