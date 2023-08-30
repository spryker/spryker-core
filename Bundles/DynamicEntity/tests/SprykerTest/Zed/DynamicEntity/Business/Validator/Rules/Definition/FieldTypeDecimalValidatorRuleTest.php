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
use Spryker\Zed\DynamicEntity\Business\Validator\Rules\Definition\FieldTypeDecimalValidatorRule;

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
 * @group FieldTypeDecimalValidatorRuleTest
 * Add your own group annotations below this line
 * @group geega
 */
class FieldTypeDecimalValidatorRuleTest extends Unit
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
        $validatorRule = new FieldTypeDecimalValidatorRule();
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
        $validatorRule = new FieldTypeDecimalValidatorRule();
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
        $validatorRule = new FieldTypeDecimalValidatorRule();
        $dynamicEntityFieldDefinitionTransfer = (new DynamicEntityFieldDefinitionTransfer())
            ->setFieldName(static::FOO_FIELD_NAME)
            ->setFieldVisibleName(static::FOO_FIELD_NAME)
            ->setType('decimal')
            ->setIsCreatable(false)
            ->setIsEditable(false)
            ->setValidation(
                (new DynamicEntityFieldValidationTransfer())
                    ->setIsRequired(true)
                    ->setPrecision(10)
                    ->setScale(2),
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
    public function testValidateCollectionWithOneFieldWillReturnWithisRequiredError(): void
    {
        // Arrange
        $validatorRule = new FieldTypeDecimalValidatorRule();
        $dynamicEntityFieldDefinitionTransfer = (new DynamicEntityFieldDefinitionTransfer())
            ->setFieldName(static::FOO_FIELD_NAME)
            ->setFieldVisibleName(static::FOO_FIELD_NAME)
            ->setType('decimal')
            ->setIsCreatable(true)
            ->setIsEditable(true)
            ->setValidation((new DynamicEntityFieldValidationTransfer())->setMaxLength(10)->setMinLength(2));

        $dynamicEntityConfigurationCollectionRequestTransfer = $this->tester->createDynamicEntityConfigurationCollectionRequestTransferByDynamicEntityFieldDefinitionTransfer($dynamicEntityFieldDefinitionTransfer);

        // Act
        $errorCollectionTransfer = $validatorRule->validate($dynamicEntityConfigurationCollectionRequestTransfer->getDynamicEntityConfigurations());

        // Assert
        $this->assertCount(1, $errorCollectionTransfer->getErrors());
        $this->assertSame('Validation rule(s): `maxLength,minLength` is not allowed for field type `decimal`.', $errorCollectionTransfer->getErrors()[0]->getMessage());
        $this->assertSame(static::FOO_FIELD_NAME, $errorCollectionTransfer->getErrors()[0]->getEntityIdentifier());
    }

    /**
     * @return void
     */
    public function testValidateCollectionWithOneFieldWillReturnWithErrors(): void
    {
        // Arrange
        $validatorRule = new FieldTypeDecimalValidatorRule();
        $dynamicEntityFieldDefinitionTransfer = (new DynamicEntityFieldDefinitionTransfer())
            ->setFieldName(static::FOO_FIELD_NAME)
            ->setFieldVisibleName(static::FOO_FIELD_NAME)
            ->setType('decimal')
            ->setIsCreatable(true)
            ->setIsEditable(true)
            ->setValidation(
                (new DynamicEntityFieldValidationTransfer())
                    ->setIsRequired(true)
                    ->setPrecision(10)
                    ->setScale(20),
            );

        $dynamicEntityConfigurationCollectionRequestTransfer = $this->tester->createDynamicEntityConfigurationCollectionRequestTransferByDynamicEntityFieldDefinitionTransfer($dynamicEntityFieldDefinitionTransfer);

        // Act
        $errorCollectionTransfer = $validatorRule->validate($dynamicEntityConfigurationCollectionRequestTransfer->getDynamicEntityConfigurations());

        // Assert
        $this->assertCount(1, $errorCollectionTransfer->getErrors());
        $this->assertSame('Validation setting `scale` must be less than `precision` for `decimal` field type.', $errorCollectionTransfer->getErrors()[0]->getMessage());
        $this->assertSame(static::FOO_FIELD_NAME, $errorCollectionTransfer->getErrors()[0]->getEntityIdentifier());
    }
}
