<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Propel\Business;

use Codeception\Test\Unit;
use DOMDocument;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Propel;

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
    public function testAdjustPropelSchemaFilesForPostgresqlAddsIdMethodParameter(): void
    {
        $initialXml = file_get_contents($this->getFixturesPathToFile('postgresql_adjuster.spy_foo.schema.xml'));
        $entityDirectory = $this->tester->getVirtualDirectory(['schemas' => ['spy_foo.schema.xml' => $initialXml]]);
        $this->tester->mockConfigMethod('getPropelSchemaPathPatterns', function () use ($entityDirectory) {
            return [$entityDirectory . '/schemas'];
        });

        $expectedXml = file_get_contents($this->getFixturesPathToFile('expected.postgresql_adjuster.spy_foo.schema.xml'));

        $this->assertNotSame($expectedXml, $this->formatXml($this->tester->getVirtualDirectoryFileContent('schemas/spy_foo.schema.xml')));
        $this->tester->getFacade()->adjustPropelSchemaFilesForPostgresql();
        $this->assertSame($expectedXml, $this->formatXml($this->tester->getVirtualDirectoryFileContent('schemas/spy_foo.schema.xml')));
    }

    /**
     * @return void
     */
    public function testAdjustPostgresqlFunctionsAddsFunction(): void
    {
        $entityDirectory = $this->tester->getVirtualDirectory();
        $this->tester->mockConfigMethod('getPropelSchemaPathPatterns', function () use ($entityDirectory) {
            return [$entityDirectory];
        });

        $connection = Propel::getConnection();
        $connection->exec('DROP AGGREGATE IF EXISTS group_concat(anyelement);');
        $connection->exec('DROP FUNCTION IF EXISTS text_add(text, anyelement);');

        $this->assertFalse($this->executeExistsQuery($connection, 'text_add'));
        $this->assertFalse($this->executeExistsQuery($connection, 'group_concat'));

        $this->tester->getFacade()->adjustPostgresqlFunctions();

        $this->assertTrue($this->executeExistsQuery($connection, 'text_add'));
        $this->assertTrue($this->executeExistsQuery($connection, 'group_concat'));
    }

    /**
     * @throws void
     */
    public function testDeleteMigrationFilesDirectoryShouldRemoveMigrationDirectory(): void
    {
        $schemaDirectory = $this->tester->getVirtualDirectory();

        $this->tester->mockConfigMethod('getMigrationDirectory', function () use ($schemaDirectory) {
            return $schemaDirectory;
        });

        $this->assertTrue(is_dir($schemaDirectory));
        $this->tester->getFacade()->deleteMigrationFilesDirectory();
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

    protected function getFixturesPathToFile(string $fileName): string
    {
        $pathParts = [
            __DIR__,
            'Fixtures',
            'PropelSchema',
        ];

        return implode(DIRECTORY_SEPARATOR, $pathParts) . DIRECTORY_SEPARATOR . $fileName;
    }

    /**
     * @param string $xml
     *
     * @return string
     */
    protected function formatXml(string $xml): string
    {
        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml);

        $callback = function ($matches) {
            $multiplier = (strlen($matches[1]) / 2) * 4;

            return str_repeat(' ', $multiplier) . '<';
        };

        return preg_replace_callback('/^( +)</m', $callback, $dom->saveXML());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Propel\Runtime\Connection\ConnectionInterface
     */
    protected function createConnectionMock(): ConnectionInterface
    {
        return $this->getMockBuilder(ConnectionInterface::class)->getMock();
    }
}
