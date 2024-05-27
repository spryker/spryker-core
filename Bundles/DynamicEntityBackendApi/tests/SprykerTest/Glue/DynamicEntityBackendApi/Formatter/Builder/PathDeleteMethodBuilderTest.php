<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\DynamicEntityBackendApi\Formatter\Builder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Spryker\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiConfig;
use Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder\PathDeleteMethodBuilder;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group DynamicEntityBackendApi
 * @group Formatter
 * @group Builder
 * @group PathDeleteMethodBuilderTest
 * Add your own group annotations below this line
 */
class PathDeleteMethodBuilderTest extends Unit
{
    /**
     * @var string
     */
    protected const CONFIG_METHOD_NAME = 'getRoutePrefix';

    /**
     * @var string
     */
    protected const PATH_METHOD_NAME = 'PathDeleteMethod';

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

        $builder = new PathDeleteMethodBuilder($configMock, $this->tester->createDynamicEntityConfigurationTreeBuilder(), $this->tester->createSchemaBuilder());

        // Act
        $formattedPathData = $builder->buildPathData($this->tester->createDynamicEntityConfigurationTransfer());

        // Assert
        $this->assertIsArray($formattedPathData);
        $this->assertEquals($this->tester->getExpectedPathData('expectedDeletePathDataWithRoutePrefix.php', static::PATH_METHOD_NAME), $formattedPathData);
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

        $builder = new PathDeleteMethodBuilder($configMock, $this->tester->createDynamicEntityConfigurationTreeBuilder(), $this->tester->createSchemaBuilder());

        // Act
        $formattedPathData = $builder->buildPathData($this->tester->createDynamicEntityConfigurationTransfer());

        // Assert
        $this->assertIsArray($formattedPathData);
        $this->assertEquals($this->tester->getExpectedPathData('expectedDeletePathDataWithoutRoutePrefix.php', static::PATH_METHOD_NAME), $formattedPathData);
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
            ->willReturn('xxx');

        $builder = new PathDeleteMethodBuilder($configMock, $this->tester->createDynamicEntityConfigurationTreeBuilder(), $this->tester->createSchemaBuilder());

        // Act
        $builder->buildPathData(new DynamicEntityConfigurationTransfer());
    }
}
