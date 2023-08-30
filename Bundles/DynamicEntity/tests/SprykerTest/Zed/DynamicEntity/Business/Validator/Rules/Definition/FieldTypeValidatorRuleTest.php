<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DynamicEntity\Business\Validator\Rules\Definition;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldValidationTransfer;
use Spryker\Zed\DynamicEntity\Business\Validator\Rules\Definition\FieldTypeValidatorRule;

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
 * @group FieldTypeValidatorRuleTest
 * Add your own group annotations below this line
 */
class FieldTypeValidatorRuleTest extends Unit
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
        $validatorRule = new FieldTypeValidatorRule();
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
        $validatorRule = new FieldTypeValidatorRule();
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
        $validatorRule = new FieldTypeValidatorRule();

        $dynamicEntityConfigurationCollectionRequestTransfer = $this->createDynamicEntityConfigurationCollectionRequestTransfer();

        // Act
        $errorCollectionTransfer = $validatorRule->validate($dynamicEntityConfigurationCollectionRequestTransfer->getDynamicEntityConfigurations());

        // Assert
        $this->assertCount(1, $errorCollectionTransfer->getErrors());
        $errorTransfer = $errorCollectionTransfer->getErrors()[0];
        $this->assertEquals('Field type is not allowed for dynamic entity. Type: number', $errorTransfer->getMessage());
        $this->assertEquals('number', $errorTransfer->getEntityIdentifier());
    }

    /**
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionRequestTransfer
     */
    protected function createDynamicEntityConfigurationCollectionRequestTransfer(): DynamicEntityConfigurationCollectionRequestTransfer
    {
        $dynamicEntityConfigurationCollectionRequestTransfer = $this->tester->createDynamicEntityConfigurationCollectionRequestTransfer();
        $dynamicEntityConfigurationTransfer = $dynamicEntityConfigurationCollectionRequestTransfer->getDynamicEntityConfigurations()[0];
        $dynamicEntityDefinitionTransfer = $dynamicEntityConfigurationTransfer->getDynamicEntityDefinition();

        $numberDynamicEntityFieldDefinitionTransfer = (new DynamicEntityFieldDefinitionTransfer())
            ->setFieldName('field_number')
            ->setFieldVisibleName('field_number')
            ->setType('number')
            ->setIsCreatable(false)
            ->setIsEditable(false)
            ->setValidation((new DynamicEntityFieldValidationTransfer())
                ->setIsRequired(false));

        $dynamicEntityDefinitionTransfer->addFieldDefinition($numberDynamicEntityFieldDefinitionTransfer);
        $dynamicEntityConfigurationTransfer->setDynamicEntityDefinition($dynamicEntityDefinitionTransfer);
        $dynamicEntityConfigurationCollectionRequestTransfer->setDynamicEntityConfigurations(new ArrayObject([$dynamicEntityConfigurationTransfer]));

        return $dynamicEntityConfigurationCollectionRequestTransfer;
    }
}
