<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\DynamicEntityBackendApi\Formatter\Builder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Spryker\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiConfig;
use Spryker\Glue\DynamicEntityBackendApi\Exception\MissingFieldDefinitionException;
use Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder\PathGetMethodBuilder;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group DynamicEntityBackendApi
 * @group Formatter
 * @group Builder
 * @group PathGetMethodBuilderTest
 * Add your own group annotations below this line
 */
class PathGetMethodBuilderTest extends Unit
{
    /**
     * @var string
     */
    protected const CONFIG_METHOD_NAME = 'getRoutePrefix';

    /**
     * @var string
     */
    protected const PATH_METHOD_NAME = 'PathGetMethod';

    /**
     * @var \SprykerTest\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testBuildPathDataFormatsPathDataWithRoutePrefix(): void
    {
        // Arrange
        $configMock = $this->getMockBuilder(
            DynamicEntityBackendApiConfig::class,
        )->getMock();

        $configMock
            ->method(static::CONFIG_METHOD_NAME)
            ->willReturn('dynamic-entity-prefix');

        $builder = new PathGetMethodBuilder($configMock, $this->tester->createDynamicEntityConfigurationTreeBuilder(), $this->tester->createSchemaBuilder());

        // Act
        $formattedPathData = $builder->buildPathData($this->tester->createDynamicEntityConfigurationTransfer());

        // Assert
        $this->assertEquals($this->tester->getExpectedPathData('expectedGetPathDataWithRoutePrefix.php', static::PATH_METHOD_NAME), $formattedPathData);
    }

    /**
     * @return void
     */
    public function testBuildPathDataFormatsPathDataWithOneChildRelation(): void
    {
        // Arrange
        $configMock = $this->getMockBuilder(
            DynamicEntityBackendApiConfig::class,
        )->getMock();

        $configMock
            ->method(static::CONFIG_METHOD_NAME)
            ->willReturn('dynamic-entity-prefix');

        $builder = new PathGetMethodBuilder($configMock, $this->tester->createDynamicEntityConfigurationTreeBuilder(), $this->tester->createSchemaBuilder());

        // Act
        $formattedPathData = $builder->buildPathData($this->tester->createDynamicEntityConfigurationTransferWithChildRelation());

        // Assert
        $this->assertEquals($this->tester->getExpectedPathData('expectedGetPathDataWithChildRelation.php', static::PATH_METHOD_NAME), $formattedPathData);
    }

    /**
     * @return void
     */
    public function testBuildPathDataFormatsPathDataWithChildRelations(): void
    {
        // Arrange
        $configMock = $this->getMockBuilder(
            DynamicEntityBackendApiConfig::class,
        )->getMock();

        $configMock
            ->method(static::CONFIG_METHOD_NAME)
            ->willReturn('dynamic-entity-prefix');

        $builder = new PathGetMethodBuilder($configMock, $this->tester->createDynamicEntityConfigurationTreeBuilder(), $this->tester->createSchemaBuilder());

        // Act
        $formattedPathData = $builder->buildPathData($this->tester->createDynamicEntityConfigurationTransferWithChildRelations());

        // Assert
        $this->assertEquals($this->tester->getExpectedPathData('expectedGetPathDataWithChildRelations.php', static::PATH_METHOD_NAME), $formattedPathData);
    }

    /**
     * @return void
     */
    public function testBuildPathDataFormatsPathDataWithChildRelationsTree(): void
    {
        // Arrange
        $configMock = $this->getMockBuilder(
            DynamicEntityBackendApiConfig::class,
        )->getMock();

        $configMock
            ->method(static::CONFIG_METHOD_NAME)
            ->willReturn('dynamic-entity-prefix');

        $builder = new PathGetMethodBuilder($configMock, $this->tester->createDynamicEntityConfigurationTreeBuilder(), $this->tester->createSchemaBuilder());

        // Act
        $formattedPathData = $builder->buildPathData($this->tester->createDynamicEntityConfigurationTransferWithChildRelations());

        // Assert
        $this->assertEquals($this->tester->getExpectedPathData('expectedGetPathDataWithChildRelationsTree.php', static::PATH_METHOD_NAME), $formattedPathData);
    }

    /**
     * @return void
     */
    public function testBuildPathDataFormatsPathDataWithoutRoutePrefix(): void
    {
        // Arrange
        $configMock = $this->getMockBuilder(DynamicEntityBackendApiConfig::class)
            ->getMock();

        $configMock
            ->method(static::CONFIG_METHOD_NAME)
            ->willReturn('');

        $builder = new PathGetMethodBuilder($configMock, $this->tester->createDynamicEntityConfigurationTreeBuilder(), $this->tester->createSchemaBuilder());

        // Act
        $formattedPathData = $builder->buildPathData($this->tester->createDynamicEntityConfigurationTransfer());

        // Assert
        $this->assertEquals($this->tester->getExpectedPathData('expectedGetPathDataWithoutRoutePrefix.php', static::PATH_METHOD_NAME), $formattedPathData);
    }

    /**
     * @return void
     */
    public function testBuildPathDataThrowsNullValueException(): void
    {
        // Assert
        $this->expectException(NullValueException::class);

        // Arrange
        $configMock = $this->getMockBuilder(DynamicEntityBackendApiConfig::class)
            ->getMock();

        $configMock
            ->method(static::CONFIG_METHOD_NAME)
            ->willReturn('');

        $builder = new PathGetMethodBuilder($configMock, $this->tester->createDynamicEntityConfigurationTreeBuilder(), $this->tester->createSchemaBuilder());

        // Act
        $builder->buildPathData(new DynamicEntityConfigurationTransfer());
    }

    /**
     * @return void
     */
    public function testBuildPathDataWithChildRelationsThrowsNullValueException(): void
    {
        // Assert
        $this->expectException(NullValueException::class);

        // Arrange
        $configMock = $this->getMockBuilder(DynamicEntityBackendApiConfig::class)
            ->getMock();

        $configMock
            ->method(static::CONFIG_METHOD_NAME)
            ->willReturn('');

        $builder = new PathGetMethodBuilder($configMock, $this->tester->createDynamicEntityConfigurationTreeBuilder(), $this->tester->createSchemaBuilder());
        $dynamicEntityConfigurationTransfer = $this->tester->createDynamicEntityConfigurationTransfer();

        // Act
        $builder->buildPathData($dynamicEntityConfigurationTransfer->addChildRelation(
            (new DynamicEntityConfigurationRelationTransfer())->setName('child')->setIsEditable(true),
        ));
    }

    /**
     * @return void
     */
    public function testBuildPathDataThrowsMissingFieldDefinitionException(): void
    {
        // Assert
        $this->expectException(MissingFieldDefinitionException::class);

        // Arrange
        $configMock = $this->getMockBuilder(DynamicEntityBackendApiConfig::class)
            ->getMock();

        $configMock
            ->method(static::CONFIG_METHOD_NAME)
            ->willReturn('dynamic-entity');

        $builder = new PathGetMethodBuilder($configMock, $this->tester->createDynamicEntityConfigurationTreeBuilder(), $this->tester->createSchemaBuilder());

        // Act
        $builder->buildPathData($this->tester->createDynamicEntityConfigurationTransferWithEmptyFieldDefinitions());
    }
}
