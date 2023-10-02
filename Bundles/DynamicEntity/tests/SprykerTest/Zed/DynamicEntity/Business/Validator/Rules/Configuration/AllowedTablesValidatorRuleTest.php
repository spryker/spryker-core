<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DynamicEntity\Business\Validator\Rules\Configuration;

use ArrayObject;
use Codeception\Test\Unit;
use Spryker\Zed\DynamicEntity\Business\Reader\DisallowedTablesReader;
use Spryker\Zed\DynamicEntity\Business\Validator\Rules\Configuration\AllowedTablesValidatorRule;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DynamicEntity
 * @group Business
 * @group Validator
 * @group Rules
 * @group Configuration
 * @group AllowedTablesValidatorRuleTest
 * Add your own group annotations below this line
 */
class AllowedTablesValidatorRuleTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\DynamicEntity\DynamicEntityBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testValidateEmptyCollectionWillReturnWithoutErrors(): void
    {
        // Arrange
        $disallowedReader = new DisallowedTablesReader($this->tester->getModuleConfig());
        $validatorRule = new AllowedTablesValidatorRule($this->tester->getModuleConfig(), $disallowedReader);
        $dynamicEntityConfigurationTransfers = new ArrayObject();

        // Act
        $errorCollectionTransfer = $validatorRule->validate($dynamicEntityConfigurationTransfers);

        // Assert
        $this->assertCount(0, $errorCollectionTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testValidateCollectionWillReturnWithoutErrors(): void
    {
        // Arrange
        $disallowedReader = new DisallowedTablesReader($this->tester->getModuleConfig());
        $validatorRule = new AllowedTablesValidatorRule($this->tester->getModuleConfig(), $disallowedReader);
        $dynamicEntityConfigurationCollectionRequestTransfer = $this->tester->createDynamicEntityConfigurationCollectionRequestTransfer();

        // Act
        $errorCollectionTransfer = $validatorRule->validate($dynamicEntityConfigurationCollectionRequestTransfer->getDynamicEntityConfigurations());

        // Assert
        $this->assertCount(0, $errorCollectionTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testValidateCollectionWillReturnWithErrors(): void
    {
        // Arrange
        $disallowedReader = new DisallowedTablesReader($this->tester->getModuleConfig());
        $validatorRule = new AllowedTablesValidatorRule($this->tester->getModuleConfig(), $disallowedReader);
        $dynamicEntityConfigurationCollectionRequestTransfer = $this->tester->createDynamicEntityConfigurationCollectionRequestTransfer('spy_dynamic_entity_configuration');

        // Act
        $errorCollectionTransfer = $validatorRule->validate($dynamicEntityConfigurationCollectionRequestTransfer->getDynamicEntityConfigurations());

        // Assert
        $this->assertCount(1, $errorCollectionTransfer->getErrors());
        $errorTransfer = $errorCollectionTransfer->getErrors()[0];
        $this->assertSame('Table name is not allowed for dynamic entity. Table: spy_dynamic_entity_configuration', $errorTransfer->getMessage());
        $this->assertSame('spy_dynamic_entity_configuration', $errorTransfer->getEntityIdentifier());
    }
}
