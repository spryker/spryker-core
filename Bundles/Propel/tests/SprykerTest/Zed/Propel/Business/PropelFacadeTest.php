<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Propel\Business;

use Codeception\Test\Unit;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Propel;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\Propel\PropelConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Propel
 * @group Business
 * @group Facade
 * @group PropelFacadeTest
 * Add your own group annotations below this line
 */
class PropelFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Propel\PropelBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCleanPropelSchemaDirectoryShouldRemoveSchemaDirectoryAndAllFilesInIt(): void
    {
        $schemaDirectory = $this->tester->getVirtualDirectory();

        $this->tester->mockConfigMethod('getSchemaDirectory', function () use ($schemaDirectory) {
            return $schemaDirectory;
        });

        $this->assertTrue(is_dir($schemaDirectory));
        $this->tester->getFacade()->cleanPropelSchemaDirectory();
        $this->assertFalse(is_dir($schemaDirectory));
    }

    /**
     * @return void
     */
    public function testCopySchemaFilesToTargetDirectoryShouldCollectAllSchemaFilesMergeAndCopyThemToSpecifiedDirectory(): void
    {
        $schemaDirectory = $this->tester->getVirtualDirectory();

        $this->tester->mockConfigMethod('getSchemaDirectory', function () use ($schemaDirectory) {
            return $schemaDirectory;
        });

        $this->tester->getFacade()->copySchemaFilesToTargetDirectory();
        $this->assertTrue(is_dir($schemaDirectory));
    }

    /**
     * @return void
     */
    public function testAdjustPropelSchemaFilesForPostgresqlShouldAddIdMethodParameter(): void
    {
        // Arrange
        $initialXml = file_get_contents($this->getFixturesPathToFile('postgresql_adjuster.spy_foo.schema.xml'));
        $entityDirectory = $this->tester->getVirtualDirectory(['schemas' => ['spy_foo.schema.xml' => $initialXml]]);
        $this->tester->mockConfigMethod('getPropelSchemaPathPatterns', function () use ($entityDirectory) {
            return [$entityDirectory . '/schemas'];
        });

        $expectedXml = file_get_contents($this->getFixturesPathToFile('expected.postgresql_adjuster.spy_foo.schema.xml'));

        $actualXml = $this->tester->formatXml($this->tester->getVirtualDirectoryFileContent('schemas/spy_foo.schema.xml'));
        $this->assertNotSame($expectedXml, $actualXml);

        // Act
        $this->tester->getFacade()->adjustPropelSchemaFilesForPostgresql();

        // Assert
        $actualXml = $this->tester->formatXml($this->tester->getVirtualDirectoryFileContent('schemas/spy_foo.schema.xml'));
        $this->assertSame($expectedXml, $actualXml);
    }

    /**
     * @return void
     */
    public function testAdjustPostgresqlFunctionsShouldAddFunctions(): void
    {
        if (Config::get(PropelConstants::ZED_DB_ENGINE) !== PropelConfig::DB_ENGINE_PGSQL) {
            $this->markTestSkipped('PostgreSQL related test');
        }

        // Arrange
        $entityDirectory = $this->tester->getVirtualDirectory();
        $this->tester->mockConfigMethod('getPropelSchemaPathPatterns', function () use ($entityDirectory) {
            return [$entityDirectory];
        });

        $connection = Propel::getConnection();
        $connection->exec('DROP AGGREGATE IF EXISTS group_concat(anyelement);');
        $connection->exec('DROP FUNCTION IF EXISTS text_add(text, anyelement);');

        $this->assertFalse($this->executeExistsQuery($connection, 'text_add'));
        $this->assertFalse($this->executeExistsQuery($connection, 'group_concat'));

        // Act
        $this->tester->getFacade()->adjustPostgresqlFunctions();

        // Assert
        $this->assertTrue($this->executeExistsQuery($connection, 'text_add'));
        $this->assertTrue($this->executeExistsQuery($connection, 'group_concat'));
    }

    /**
     * @return void
     */
    public function testDeleteMigrationFilesDirectoryShouldRemoveMigrationDirectory(): void
    {
        // Arrange
        $schemaDirectory = $this->tester->getVirtualDirectory();
        $this->tester->mockConfigMethod('getMigrationDirectory', function () use ($schemaDirectory) {
            return $schemaDirectory;
        });
        $this->assertTrue(is_dir($schemaDirectory));

        // Act
        $this->tester->getFacade()->deleteMigrationFilesDirectory();

        // Assert
        $this->assertFalse(is_dir($schemaDirectory));
    }

    /**
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     * @param string $name
     *
     * @return bool
     */
    protected function executeExistsQuery(ConnectionInterface $connection, string $name): bool
    {
        $sqlQuery = "SELECT exists(SELECT * FROM pg_proc WHERE proname = '$name')";
        $statement = $connection->prepare($sqlQuery);
        $statement->execute();

        return $statement->fetch()['exists'] ?? false;
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    protected function getFixturesPathToFile(string $fileName): string
    {
        $pathParts = [
            __DIR__,
            'Fixtures',
            'PropelSchema',
            $fileName,
        ];

        return implode(DIRECTORY_SEPARATOR, $pathParts);
    }
}
