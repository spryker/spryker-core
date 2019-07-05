<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Development\Business\Dependency\SchemaParser;

use Codeception\Configuration;
use Codeception\Stub;
use Codeception\Test\Unit;
use Spryker\Zed\Development\Business\Dependency\SchemaParser\PropelSchemaParser;
use Spryker\Zed\Development\Business\Exception\Dependency\PropelSchemaParserException;
use Spryker\Zed\Development\DevelopmentConfig;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Development
 * @group Business
 * @group Dependency
 * @group SchemaParser
 * @group PropelSchemaParserTest
 * Add your own group annotations below this line
 */
class PropelSchemaParserTest extends Unit
{
    protected const GENERATED_SCHEMA_FILES = 'SchemaFiles';
    protected const PROPEL_SCHEMA_PATH_PATTERN = '*/%s';

    /**
     * @var \SprykerTest\Zed\Development\DevelopmentBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetForeignColumnNamesForSchemaWithNamespaceReturnsForeignReferenceColumnNames(): void
    {
        // Arrange
        $propelSchemaParser = $this->getPropelSchemaParser();
        $fileInfo = new SplFileInfo(Configuration::dataDir() . 'SchemaWithDependencies/test.schema.xml', '', '');
        $expectedResult = [
            0 => 'spy_zip_zap.id_zip_zap',
            1 => 'spy_qux_quux.id_qux_quux',
        ];

        // Act
        $foreignColumnNames = $propelSchemaParser->getForeignColumnNames($fileInfo);

        // Assert
        $this->assertEquals($expectedResult, $foreignColumnNames);
    }

    /**
     * @return void
     */
    public function testGetForeignColumnNamesForStaleSchemaReturnsForeignReferenceColumnNames(): void
    {
        // Arrange
        $propelSchemaParser = $this->getPropelSchemaParser();
        $fileInfo = new SplFileInfo(Configuration::dataDir() . 'StaleSchemaWithDependencies/test.schema.xml', '', '');
        $expectedResult = [
            0 => 'spy_zip_zap.id_zip_zap',
            1 => 'spy_qux_quux.id_qux_quux',
        ];

        // Act
        $foreignColumnNames = $propelSchemaParser->getForeignColumnNames($fileInfo);

        // Assert
        $this->assertEquals($expectedResult, $foreignColumnNames);
    }

    /**
     * @return void
     */
    public function testGetModuleNameByForeignReferenceReturnsAllDependentModulesBothStaleAnsNamespacedOnes(): void
    {
        // Arrange
        $propelSchemaParser = $this->getPropelSchemaParser();
        $foreignIdColumnNames = [
            0 => 'spy_zip_zap.id_zip_zap',
            1 => 'spy_qux_quux.id_qux_quux',
        ];
        $expectedResult = [
            0 => 'ZipZap',
            1 => 'QuxQuux',
        ];

        $dependentModules = [];

        // Act
        foreach ($foreignIdColumnNames as $foreignIdColumnName) {
            $dependentModules[] = $propelSchemaParser->getModuleNameByForeignReference($foreignIdColumnName, 'FooBar');
        }

        // Assert
        $this->assertEquals($expectedResult, $dependentModules);
    }

    /**
     * @return void
     */
    public function testGetModuleNameByForeignReferenceForUnexistingReferencesThrowsPropelSchemaParserException(): void
    {
        // Arrange
        $propelSchemaParser = $this->getPropelSchemaParser();
        $foreignIdColumnNames = [
            0 => 'spy_zip_zap.id_zip_zap',
            1 => 'spy_baz_xyzzy.id_baz_xyzzy',
        ];

        // Assert
        $this->expectException(PropelSchemaParserException::class);

        // Act
        foreach ($foreignIdColumnNames as $foreignIdColumnName) {
            $dependentModules[] = $propelSchemaParser->getModuleNameByForeignReference($foreignIdColumnName, 'FooBar');
        }
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\SchemaParser\PropelSchemaParser
     */
    protected function getPropelSchemaParser(): PropelSchemaParser
    {
        /** @var \Spryker\Zed\Development\DevelopmentConfig $developmentConfigMock */
        $developmentConfigMock = Stub::make(DevelopmentConfig::class, [
            'getOrganizationPathMap' => function () {
                return [
                    'Spryker' => Configuration::dataDir() . static::GENERATED_SCHEMA_FILES . DIRECTORY_SEPARATOR,
                ];
            },
        ]);

        return new PropelSchemaParser($developmentConfigMock);
    }
}
