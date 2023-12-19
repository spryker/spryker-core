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
use Spryker\Zed\DynamicEntity\Business\Validator\Rules\Definition\FieldTypeBooleanValidatorRule;

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
 * @group FieldTypeBooleanValidatorRuleTest
 * Add your own group annotations below this line
 */
class FieldTypeBooleanValidatorRuleTest extends Unit
{
    /**
     * @var string
     */
    protected const FOO_FIELD_NAME = 'foo';

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
        $validatorRule = new FieldTypeBooleanValidatorRule();
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
        $validatorRule = new FieldTypeBooleanValidatorRule();
        $dynamicEntityConfigurationCollectionRequestTransfer = $this->tester->createDynamicEntityConfigurationCollectionRequestTransfer();

        // Act
        $errorCollectionTransfer = $validatorRule->validate($dynamicEntityConfigurationCollectionRequestTransfer->getDynamicEntityConfigurations());

        // Assert
        $this->assertCount(0, $errorCollectionTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testValidateCollectionWithOneFieldWillReturnWithoutErrors(): void
    {
        // Arrange
        $validatorRule = new FieldTypeBooleanValidatorRule();
        $dynamicEntityFieldDefinitionTransfer = (new DynamicEntityFieldDefinitionTransfer())
            ->setFieldName(static::FOO_FIELD_NAME)
            ->setFieldVisibleName(static::FOO_FIELD_NAME)
            ->setType('boolean')
            ->setIsCreatable(false)
            ->setIsEditable(false)
            ->setValidation(
                (new DynamicEntityFieldValidationTransfer())->setIsRequired(true),
            );

        $dynamicEntityConfigurationCollectionRequestTransfer = $this->tester->createDynamicEntityConfigurationCollectionRequestTransferByDynamicEntityFieldDefinitionTransfer($dynamicEntityFieldDefinitionTransfer);

        // Act
        $errorCollectionTransfer = $validatorRule->validate($dynamicEntityConfigurationCollectionRequestTransfer->getDynamicEntityConfigurations());

        // Assert
        $this->assertCount(0, $errorCollectionTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testValidateCollectionWithOneFieldWillReturnWithErrors(): void
    {
        // Arrange
        $validatorRule = new FieldTypeBooleanValidatorRule();
        $dynamicEntityFieldDefinitionTransfer = (new DynamicEntityFieldDefinitionTransfer())
            ->setFieldName(static::FOO_FIELD_NAME)
            ->setFieldVisibleName(static::FOO_FIELD_NAME)
            ->setType('boolean')
            ->setIsCreatable(false)
            ->setIsEditable(false)
            ->setValidation(
                (new DynamicEntityFieldValidationTransfer())
                ->setMinLength(1)
                ->setMaxLength(255),
            );

        $dynamicEntityConfigurationCollectionRequestTransfer = $this->tester->createDynamicEntityConfigurationCollectionRequestTransferByDynamicEntityFieldDefinitionTransfer($dynamicEntityFieldDefinitionTransfer);

        // Act
        $errorCollectionTransfer = $validatorRule->validate($dynamicEntityConfigurationCollectionRequestTransfer->getDynamicEntityConfigurations());

        // Assert
        $this->assertCount(1, $errorCollectionTransfer->getErrors());
        $this->assertSame('Validation rule(s): `maxLength,minLength` is not allowed for field type `boolean`.', $errorCollectionTransfer->getErrors()[0]->getMessage());
        $this->assertSame(static::FOO_FIELD_NAME, $errorCollectionTransfer->getErrors()[0]->getEntityIdentifier());
    }
}
