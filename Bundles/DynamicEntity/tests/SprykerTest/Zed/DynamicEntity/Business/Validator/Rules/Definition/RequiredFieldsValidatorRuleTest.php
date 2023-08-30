<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DynamicEntity\Business\Validator\Rules\Definition;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldValidationTransfer;
use Spryker\Zed\DynamicEntity\Business\Validator\Rules\Definition\RequiredFieldsValidatorRule;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DynamicEntity
 * @group Business
 * @group Validator
 * @group Rules
 * @group Definition
 * @group RequiredFieldsValidatorRuleTest
 * Add your own group annotations below this line
 */
class RequiredFieldsValidatorRuleTest extends Unit
{
    /**
     * @var string
     */
    protected const ENTITY_IDENTIFIER = 'spy_xxxx.row:1';

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
        $validatorRule = new RequiredFieldsValidatorRule();
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
        $validatorRule = new RequiredFieldsValidatorRule();
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
        $validatorRule = new RequiredFieldsValidatorRule();
        $dynamicEntityFieldDefinitionTransfer = (new DynamicEntityFieldDefinitionTransfer())->setValidation(new DynamicEntityFieldValidationTransfer());
        $dynamicEntityConfigurationCollectionRequestTransfer = $this->tester->createDynamicEntityConfigurationCollectionRequestTransferByDynamicEntityFieldDefinitionTransfer($dynamicEntityFieldDefinitionTransfer);

        // Act
        $errorCollectionTransfer = $validatorRule->validate($dynamicEntityConfigurationCollectionRequestTransfer->getDynamicEntityConfigurations());

        // Assert
        $this->assertCount(6, $errorCollectionTransfer->getErrors());
        $this->assertSame('Identifier is not defined in field definitions.', $errorCollectionTransfer->getErrors()[0]->getMessage());
        $this->assertSame('spy_xxxx', $errorCollectionTransfer->getErrors()[0]->getEntityIdentifier());
        $this->assertSame('Field definition `type` is required for dynamic entity configuration.', $errorCollectionTransfer->getErrors()[1]->getMessage());
        $this->assertSame(static::ENTITY_IDENTIFIER, $errorCollectionTransfer->getErrors()[1]->getEntityIdentifier());
        $this->assertSame('Field definition `fieldName` is required for dynamic entity configuration.', $errorCollectionTransfer->getErrors()[2]->getMessage());
        $this->assertSame(static::ENTITY_IDENTIFIER, $errorCollectionTransfer->getErrors()[2]->getEntityIdentifier());
        $this->assertSame('Field definition `fieldVisibleName` is required for dynamic entity configuration.', $errorCollectionTransfer->getErrors()[3]->getMessage());
        $this->assertSame(static::ENTITY_IDENTIFIER, $errorCollectionTransfer->getErrors()[3]->getEntityIdentifier());
        $this->assertSame('Field definition `isCreatable` is required for dynamic entity configuration.', $errorCollectionTransfer->getErrors()[4]->getMessage());
        $this->assertSame(static::ENTITY_IDENTIFIER, $errorCollectionTransfer->getErrors()[4]->getEntityIdentifier());
        $this->assertSame('Field definition `isEditable` is required for dynamic entity configuration.', $errorCollectionTransfer->getErrors()[5]->getMessage());
        $this->assertSame(static::ENTITY_IDENTIFIER, $errorCollectionTransfer->getErrors()[5]->getEntityIdentifier());
    }
}
