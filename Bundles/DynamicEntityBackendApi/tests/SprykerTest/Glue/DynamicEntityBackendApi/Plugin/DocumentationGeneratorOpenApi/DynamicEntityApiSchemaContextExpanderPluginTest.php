<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\DynamicEntityBackendApi\Plugin\DocumentationGeneratorOpenApi;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Generated\Shared\Transfer\CustomRoutesContextTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Spryker\Glue\DynamicEntityBackendApi\Controller\DynamicEntityBackendApiController;
use Spryker\Glue\DynamicEntityBackendApi\Plugin\DocumentationGeneratorApi\DynamicEntityApiSchemaContextExpanderPlugin;
use Spryker\Glue\DynamicEntityBackendApi\Processor\Reader\DynamicEntityReaderInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group DynamicEntityBackendApi
 * @group Plugin
 * @group DocumentationGeneratorOpenApi
 * @group DynamicEntityApiSchemaContextExpanderPluginTest
 * Add your own group annotations below this line
 */
class DynamicEntityApiSchemaContextExpanderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const CONTROLLER = '_controller';

    /**
     * @var string
     */
    protected const RESOURCE_NAME_1 = 'resource-1';

    /**
     * @var string
     */
    protected const RESOURCE_NAME_2 = 'resource-2';

    /**
     * @var \SprykerTest\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandReturnsEmptyTransfer(): void
    {
        // Arrange
        $apiApplicationSchemaContextTransfer = new ApiApplicationSchemaContextTransfer();
        $plugin = $this->createDynamicEntityApiSchemaContextExpanderPlugin();

        // Act
        $newApiApplicationSchemaContextTransfer = $plugin->expand($apiApplicationSchemaContextTransfer);

        // Assert
        $this->assertSame($apiApplicationSchemaContextTransfer, $newApiApplicationSchemaContextTransfer);
    }

    /**
     * @return void
     */
    public function testExpandExpandsDynamicEntityConfigurationsItems(): void
    {
        // Arrange
        $dynamicEntityConfigurations = [
            (new DynamicEntityConfigurationTransfer())->setTableAlias(static::RESOURCE_NAME_1),
            (new DynamicEntityConfigurationTransfer())->setTableAlias(static::RESOURCE_NAME_2),
        ];

        $dynamicEntityReaderMock = $this->createMock(DynamicEntityReaderInterface::class);
        $dynamicEntityReaderMock->expects($this->once())
            ->method('getDynamicEntityConfigurations')
            ->willReturn($dynamicEntityConfigurations);

        $this->tester->mockFactoryMethod('createDynamicEntityReader', $dynamicEntityReaderMock);

        $apiApplicationSchemaContextTransfer = new ApiApplicationSchemaContextTransfer();

        $plugin = $this->createDynamicEntityApiSchemaContextExpanderPlugin();
        $plugin->setFactory($this->tester->getFactory());

        // Act
        $newApiApplicationSchemaContextTransfer = $plugin->expand($apiApplicationSchemaContextTransfer);

        // Assert
        $this->assertSame($apiApplicationSchemaContextTransfer, $newApiApplicationSchemaContextTransfer);
        $this->assertCount(2, $newApiApplicationSchemaContextTransfer->getDynamicEntityConfigurations());
        $copyArrayDynamicEntityConfigurations = $newApiApplicationSchemaContextTransfer->getDynamicEntityConfigurations()->getArrayCopy();
        $this->assertSame(static::RESOURCE_NAME_1, $copyArrayDynamicEntityConfigurations[0]->getTableAlias());
        $this->assertSame(static::RESOURCE_NAME_2, $copyArrayDynamicEntityConfigurations[1]->getTableAlias());
    }

    /**
     * @return void
     */
    public function testExpandNotExpandsDynamicEntityConfigurationsItems(): void
    {
        // Arrange
        $dynamicEntityReaderMock = $this->createMock(DynamicEntityReaderInterface::class);
        $dynamicEntityReaderMock->expects($this->once())
            ->method('getDynamicEntityConfigurations')
            ->willReturn([]);

        $this->tester->mockFactoryMethod('createDynamicEntityReader', $dynamicEntityReaderMock);

        $apiApplicationSchemaContextTransfer = new ApiApplicationSchemaContextTransfer();

        $plugin = $this->createDynamicEntityApiSchemaContextExpanderPlugin();
        $plugin->setFactory($this->tester->getFactory());

        // Act
        $newApiApplicationSchemaContextTransfer = $plugin->expand($apiApplicationSchemaContextTransfer);

        // Assert
        $this->assertSame($apiApplicationSchemaContextTransfer, $newApiApplicationSchemaContextTransfer);
        $this->assertCount(0, $newApiApplicationSchemaContextTransfer->getDynamicEntityConfigurations());
    }

    /**
     * @return void
     */
    public function testExpandFiltersDynamicEntityControllerRouter(): void
    {
        // Arrange
        $apiApplicationSchemaContextTransfer = new ApiApplicationSchemaContextTransfer();

        $dynamicEntityBackendApiCustomRoutesContextTransfer = new CustomRoutesContextTransfer();
        $dynamicEntityBackendApiCustomRoutesContextTransfer->setDefaults([
            static::CONTROLLER => [DynamicEntityBackendApiController::class, 'get'],

        ]);

        $customRoutesContextTransfer = new CustomRoutesContextTransfer();
        $customRoutesContextTransfer->setDefaults([
            static::CONTROLLER => ['/Some/Controller/Path/ControllerName', 'get'],
        ]);

        $apiApplicationSchemaContextTransfer->setCustomRoutesContexts(new ArrayObject([
            $dynamicEntityBackendApiCustomRoutesContextTransfer,
            $customRoutesContextTransfer,
        ]));

        $plugin = $this->createDynamicEntityApiSchemaContextExpanderPlugin();
        $plugin->setFactory($this->tester->getFactory());

        // Act
        $newApiApplicationSchemaContextTransfer = $plugin->expand($apiApplicationSchemaContextTransfer);

        // Assert
        $customRoutesContexts = $newApiApplicationSchemaContextTransfer->getCustomRoutesContexts()->getArrayCopy();
        $this->assertCount(1, $customRoutesContexts);
        $this->assertSame($customRoutesContextTransfer, $customRoutesContexts[1]);
    }

    /**
     * @return void
     */
    public function testExpandNotFiltersDynamicEntityControllerRouter(): void
    {
        // Arrange
        $apiApplicationSchemaContextTransfer = new ApiApplicationSchemaContextTransfer();

        $dynamicEntityBackendApiCustomRoutesContextTransfer = new CustomRoutesContextTransfer();
        $dynamicEntityBackendApiCustomRoutesContextTransfer->setDefaults([
            static::CONTROLLER => ['/Some/Controller/Path/Controller1Name', 'get'],

        ]);

        $customRoutesContextTransfer = new CustomRoutesContextTransfer();
        $customRoutesContextTransfer->setDefaults([
                static::CONTROLLER => ['/Some/Controller/Path/Controller2Name', 'get'],
        ]);

        $apiApplicationSchemaContextTransfer->setCustomRoutesContexts(new ArrayObject([
            $dynamicEntityBackendApiCustomRoutesContextTransfer,
            $customRoutesContextTransfer,
        ]));

        $plugin = $this->createDynamicEntityApiSchemaContextExpanderPlugin();
        $plugin->setFactory($this->tester->getFactory());

        // Act
        $newApiApplicationSchemaContextTransfer = $plugin->expand($apiApplicationSchemaContextTransfer);

        // Assert
        $customRoutesContexts = $newApiApplicationSchemaContextTransfer->getCustomRoutesContexts()->getArrayCopy();
        $this->assertCount(2, $customRoutesContexts);
        $this->assertSame($dynamicEntityBackendApiCustomRoutesContextTransfer, $customRoutesContexts[0]);
        $this->assertSame($customRoutesContextTransfer, $customRoutesContexts[1]);
    }

    /**
     * @return \Spryker\Glue\DynamicEntityBackendApi\Plugin\DocumentationGeneratorApi\DynamicEntityApiSchemaContextExpanderPlugin
     */
    protected function createDynamicEntityApiSchemaContextExpanderPlugin(): DynamicEntityApiSchemaContextExpanderPlugin
    {
        return new DynamicEntityApiSchemaContextExpanderPlugin();
    }
}
