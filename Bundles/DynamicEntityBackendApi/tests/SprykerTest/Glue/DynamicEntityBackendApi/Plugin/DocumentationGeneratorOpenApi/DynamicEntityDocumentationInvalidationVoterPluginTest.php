<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\DynamicEntityBackendApi\Plugin\DocumentationGeneratorOpenApi;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\DocumentationInvalidationVoterRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Spryker\Glue\DynamicEntityBackendApi\Dependency\Facade\DynamicEntityBackendApiToDynamicEntityFacadeInterface;
use Spryker\Glue\DynamicEntityBackendApi\Plugin\DocumentationGeneratorApi\DynamicEntityDocumentationInvalidationVoterPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group DynamicEntityBackendApi
 * @group Plugin
 * @group DocumentationGeneratorOpenApi
 * @group DynamicEntityDocumentationInvalidationVoterPluginTest
 * Add your own group annotations below this line
 */
class DynamicEntityDocumentationInvalidationVoterPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testIsInvalidatedWillReturnFalse(): void
    {
        // Arrange
        $dynamicEntityConfigurationCollectionTransfer = new DynamicEntityConfigurationCollectionTransfer();
        $dynamicEntityConfigurationCollectionTransfer->setDynamicEntityConfigurations(new ArrayObject([]));

        $dynamicEntityBackendApiToDynamicEntityFacadeMock = $this->createMock(DynamicEntityBackendApiToDynamicEntityFacadeInterface::class);
        $dynamicEntityBackendApiToDynamicEntityFacadeMock->expects($this->once())
            ->method('getDynamicEntityConfigurationCollection')
            ->willReturn($dynamicEntityConfigurationCollectionTransfer);

        $this->tester->mockFactoryMethod('getDynamicEntityFacade', $dynamicEntityBackendApiToDynamicEntityFacadeMock);

        $plugin = new DynamicEntityDocumentationInvalidationVoterPlugin();
        $plugin->setFactory($this->tester->getFactory());
        $documentationInvalidationVoterRequestTransfer = (new DocumentationInvalidationVoterRequestTransfer())->setInterval('1min');
        // Act
        $isInvalidated = $plugin->isInvalidated($documentationInvalidationVoterRequestTransfer);

        // Assert
        $this->assertFalse($isInvalidated);
    }

    /**
     * @return void
     */
    public function testIsInvalidatedWillReturnTrue(): void
    {
        // Arrange
        $dynamicEntityConfigurationCollectionTransfer = (new DynamicEntityConfigurationCollectionTransfer())
            ->addDynamicEntityConfiguration((new DynamicEntityConfigurationTransfer())->setTableAlias('/resource-1'))
            ->addDynamicEntityConfiguration((new DynamicEntityConfigurationTransfer())->setTableAlias('/resource-2'));

        $dynamicEntityBackendApiToDynamicEntityFacadeMock = $this->createMock(DynamicEntityBackendApiToDynamicEntityFacadeInterface::class);
        $dynamicEntityBackendApiToDynamicEntityFacadeMock->expects($this->once())
            ->method('getDynamicEntityConfigurationCollection')
            ->willReturn($dynamicEntityConfigurationCollectionTransfer);

        $this->tester->mockFactoryMethod('getDynamicEntityFacade', $dynamicEntityBackendApiToDynamicEntityFacadeMock);

        $plugin = new DynamicEntityDocumentationInvalidationVoterPlugin();
        $plugin->setFactory($this->tester->getFactory());
        $documentationInvalidationVoterRequestTransfer = (new DocumentationInvalidationVoterRequestTransfer())->setInterval('1min');
        // Act
        $isInvalidated = $plugin->isInvalidated($documentationInvalidationVoterRequestTransfer);

        // Assert
        $this->assertTrue($isInvalidated);
    }

    /**
     * @return void
     */
    public function testIsInvalidatedWithIntervalAsEmptyStringWillCallWarning(): void
    {
        // Assert
        $this->expectWarning();
        $this->expectWarningMessage('DateInterval::createFromDateString(): Unknown or bad format () at position 0 ( ): Empty string');

        // Arrange
        $dynamicEntityConfigurationCollectionTransfer = new DynamicEntityConfigurationCollectionTransfer();
        $dynamicEntityConfigurationCollectionTransfer->setDynamicEntityConfigurations(new ArrayObject([]));

        $dynamicEntityBackendApiToDynamicEntityFacadeMock = $this->createMock(DynamicEntityBackendApiToDynamicEntityFacadeInterface::class);
        $dynamicEntityBackendApiToDynamicEntityFacadeMock
            ->method('getDynamicEntityConfigurationCollection')
            ->willReturn($dynamicEntityConfigurationCollectionTransfer);

        $this->tester->mockFactoryMethod('getDynamicEntityFacade', $dynamicEntityBackendApiToDynamicEntityFacadeMock);

        $plugin = new DynamicEntityDocumentationInvalidationVoterPlugin();
        $plugin->setFactory($this->tester->getFactory());
        $documentationInvalidationVoterRequestTransfer = (new DocumentationInvalidationVoterRequestTransfer())->setInterval('');

        // Act
        $plugin->isInvalidated($documentationInvalidationVoterRequestTransfer);
    }

    /**
     * @return void
     */
    public function testIsInvalidatedWillCallException(): void
    {
        // Assert
        $this->expectWarning();
        $this->expectWarningMessage('DateInterval::createFromDateString(): Unknown or bad format (9999999) at position 4 (9): Unexpected character');

        // Arrange
        $dynamicEntityConfigurationCollectionTransfer = new DynamicEntityConfigurationCollectionTransfer();
        $dynamicEntityConfigurationCollectionTransfer->setDynamicEntityConfigurations(new ArrayObject([]));

        $dynamicEntityBackendApiToDynamicEntityFacadeMock = $this->createMock(DynamicEntityBackendApiToDynamicEntityFacadeInterface::class);
        $dynamicEntityBackendApiToDynamicEntityFacadeMock
            ->method('getDynamicEntityConfigurationCollection')
            ->willReturn($dynamicEntityConfigurationCollectionTransfer);

        $this->tester->mockFactoryMethod('getDynamicEntityFacade', $dynamicEntityBackendApiToDynamicEntityFacadeMock);

        $plugin = new DynamicEntityDocumentationInvalidationVoterPlugin();
        $plugin->setFactory($this->tester->getFactory());
        $documentationInvalidationVoterRequestTransfer = (new DocumentationInvalidationVoterRequestTransfer())->setInterval('9999999');

        // Act
        $plugin->isInvalidated($documentationInvalidationVoterRequestTransfer);
    }
}
