<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DynamicEntity\Business\Validator\Field\Type;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldValidationTransfer;
use Spryker\Zed\DynamicEntity\Business\Indexer\DynamicEntityIndexerInterface;
use Spryker\Zed\DynamicEntity\Business\Resolver\DynamicEntityErrorPathResolverInterface;
use Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface;
use Spryker\Zed\DynamicEntity\Business\Validator\Field\Type\IntegerFieldTypeValidator;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DynamicEntity
 * @group Business
 * @group Validator
 * @group Field
 * @group Type
 * @group IntegerFieldTypeValidatorTest
 * Add your own group annotations below this line
 */
class IntegerFieldTypeValidatorTest extends Unit
{
    /**
     * @return void
     */
    public function testWillReturnValidatorType(): void
    {
        // Arrange
        $validator = $this->createIntegerFieldTypeValidator();

        // Act & Assert
        $this->assertSame('integer', $validator->getType());
    }

    /**
     * @return void
     */
    public function testValidTypeWillReturnTrue(): void
    {
        // Arrange
        $validator = $this->createIntegerFieldTypeValidator();

        // Act & Assert
        $this->assertTrue($validator->isValidType(123));
        $this->assertTrue($validator->isValidType(0));
        $this->assertTrue($validator->isValidType(-123));
    }

    /**
     * @return void
     */
    public function testValidTypeWillReturnFalse(): void
    {
        // Arrange
        $validator = $this->createIntegerFieldTypeValidator();

        // Act & Assert
        $this->assertFalse($validator->isValidType('string'));
        $this->assertFalse($validator->isValidType('0'));
        $this->assertFalse($validator->isValidType('123'));
        $this->assertFalse($validator->isValidType('123.123'));
    }

    /**
     * @return void
     */
    public function testValidValueWithoutValidationRulesWillReturnTrue(): void
    {
        // Arrange
        $validator = $this->createIntegerFieldTypeValidator();
        $dynamicEntityFieldDefinitionTransfer = new DynamicEntityFieldDefinitionTransfer();

        // Act & Assert
        $this->assertTrue($validator->isValidValue(1, $dynamicEntityFieldDefinitionTransfer));
        $this->assertTrue($validator->isValidValue(-1, $dynamicEntityFieldDefinitionTransfer));
        $this->assertTrue($validator->isValidValue('1', $dynamicEntityFieldDefinitionTransfer));
        $this->assertTrue($validator->isValidValue('0', $dynamicEntityFieldDefinitionTransfer));
        $this->assertTrue($validator->isValidValue('string', $dynamicEntityFieldDefinitionTransfer));
        $this->assertTrue($validator->isValidValue(123, $dynamicEntityFieldDefinitionTransfer));
        $this->assertTrue($validator->isValidValue(123.123, $dynamicEntityFieldDefinitionTransfer));
        $this->assertTrue($validator->isValidValue(true, $dynamicEntityFieldDefinitionTransfer));
    }

    /**
     * @return void
     */
    public function testValidValueWithValidationRulesWillReturnTrue(): void
    {
        // Arrange
        $validator = $this->createIntegerFieldTypeValidator();
        $dynamicEntityFieldDefinitionTransfer = (new DynamicEntityFieldDefinitionTransfer())->setValidation(
            (new DynamicEntityFieldValidationTransfer())->setMin(0)->setMax(10),
        );

        // Act & Assert
        $this->assertTrue($validator->isValidValue(1, $dynamicEntityFieldDefinitionTransfer));
        $this->assertTrue($validator->isValidValue(9, $dynamicEntityFieldDefinitionTransfer));
        $this->assertTrue($validator->isValidValue(0, $dynamicEntityFieldDefinitionTransfer));
        $this->assertTrue($validator->isValidValue('1', $dynamicEntityFieldDefinitionTransfer));
        $this->assertTrue($validator->isValidValue('0', $dynamicEntityFieldDefinitionTransfer));
    }

    /**
     * @return void
     */
    public function testValidValueWithValidationRulesWillReturnFalse(): void
    {
        // Arrange
        $validator = $this->createIntegerFieldTypeValidator();
        $dynamicEntityFieldDefinitionTransfer = (new DynamicEntityFieldDefinitionTransfer())->setValidation(
            (new DynamicEntityFieldValidationTransfer())->setMin(-99)->setMax(99),
        );

        // Act & Assert
        $this->assertFalse($validator->isValidValue(100, $dynamicEntityFieldDefinitionTransfer));
        $this->assertFalse($validator->isValidValue(-100, $dynamicEntityFieldDefinitionTransfer));
        $this->assertFalse($validator->isValidValue('-100', $dynamicEntityFieldDefinitionTransfer));
        $this->assertFalse($validator->isValidValue('100', $dynamicEntityFieldDefinitionTransfer));
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface
     */
    protected function createIntegerFieldTypeValidator(): DynamicEntityValidatorInterface
    {
        return new IntegerFieldTypeValidator(
            $this->createDynamicEntityIndexerMock(),
            $this->createDynamicEntityErrorPathResolverMock(),
        );
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Indexer\DynamicEntityIndexerInterface
     */
    protected function createDynamicEntityIndexerMock(): DynamicEntityIndexerInterface
    {
        return $this->getMockBuilder(DynamicEntityIndexerInterface::class)->getMock();
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Resolver\DynamicEntityErrorPathResolverInterface
     */
    protected function createDynamicEntityErrorPathResolverMock(): DynamicEntityErrorPathResolverInterface
    {
        return $this->getMockBuilder(DynamicEntityErrorPathResolverInterface::class)->getMock();
    }
}
