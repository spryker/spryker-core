<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Propel\Business\Model;

use Spryker\Zed\Propel\Business\Model\PropelGroupedSchemaFinder;
use Spryker\Zed\Propel\Business\Model\PropelSchemaFinder;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Propel
 * @group Business
 * @group Model
 * @group PropelGroupedSchemaFinderTest
 * Add your own group annotations below this line
 */
class PropelGroupedSchemaFinderTest extends AbstractPropelSchemaTest
{
    public const NAME_OF_SCHEMA_FILE_GROUP = 'spy_foo.schema.xml';

    /**
     * @return void
     */
    public function testGetSchemasShouldReturnArrayWithOneEntryGroupedByFileNameIfFileWithSameNameOnlyExistsOnce()
    {
        $schemaFinder = new PropelSchemaFinder(
            [$this->getFixtureDirectory()]
        );

        $schemaGrouper = new PropelGroupedSchemaFinder(
            $schemaFinder
        );

        $groupedSchemaFiles = $schemaGrouper->getGroupedSchemaFiles();
        $this->assertIsArray($groupedSchemaFiles);
        $this->assertArrayHasKey(self::NAME_OF_SCHEMA_FILE_GROUP, $groupedSchemaFiles);
        $this->assertCount(1, $groupedSchemaFiles[self::NAME_OF_SCHEMA_FILE_GROUP]);
    }

    /**
     * @return void
     */
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
        $this->assertIsArray($groupedSchemaFiles);
        $this->assertArrayHasKey(self::NAME_OF_SCHEMA_FILE_GROUP, $groupedSchemaFiles);
        $this->assertCount(2, $groupedSchemaFiles[self::NAME_OF_SCHEMA_FILE_GROUP]);
    }
}
