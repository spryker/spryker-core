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
use Spryker\Zed\DynamicEntity\Business\Validator\Field\Type\StringFieldTypeValidator;

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
 * @group StringFieldTypeValidatorTest
 * Add your own group annotations below this line
 */
class StringFieldTypeValidatorTest extends Unit
{
    /**
     * @return void
     */
    public function testWillReturnValidatorType(): void
    {
        // Arrange
        $validator = $this->createStringFieldTypeValidator();

        // Act & Assert
        $this->assertSame('string', $validator->getType());
    }

    /**
     * @return void
     */
    public function testValidTypeWillReturnTrue(): void
    {
        // Arrange
        $validator = $this->createStringFieldTypeValidator();

        // Act & Assert
        $this->assertTrue($validator->isValidType(''));
        $this->assertTrue($validator->isValidType('string'));
        $this->assertTrue($validator->isValidType('123'));
    }

    /**
     * @return void
     */
    public function testValidTypeWillReturnFalse(): void
    {
        // Arrange
        $validator = $this->createStringFieldTypeValidator();

        // Act & Assert
        $this->assertFalse($validator->isValidType(123));
        $this->assertFalse($validator->isValidType(-123));
        $this->assertFalse($validator->isValidType(false));
    }

    /**
     * @return void
     */
    public function testValidValueWithoutValidationRulesWillReturnTrue(): void
    {
        // Arrange
        $validator = $this->createStringFieldTypeValidator();
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
        $validator = $this->createStringFieldTypeValidator();
        $dynamicEntityFieldDefinitionTransfer = (new DynamicEntityFieldDefinitionTransfer())->setValidation(
            (new DynamicEntityFieldValidationTransfer())->setMinLength(1)->setMaxLength(6),
        );

        // Act & Assert
        $this->assertTrue($validator->isValidValue('string', $dynamicEntityFieldDefinitionTransfer));
        $this->assertTrue($validator->isValidValue('123456', $dynamicEntityFieldDefinitionTransfer));
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
        $validator = $this->createStringFieldTypeValidator();
        $dynamicEntityFieldDefinitionTransfer = (new DynamicEntityFieldDefinitionTransfer())->setValidation(
            (new DynamicEntityFieldValidationTransfer())->setMinLength(1)->setMaxLength(3),
        );

        // Act & Assert
        $this->assertFalse($validator->isValidValue('', $dynamicEntityFieldDefinitionTransfer));
        $this->assertFalse($validator->isValidValue('string', $dynamicEntityFieldDefinitionTransfer));
        $this->assertFalse($validator->isValidValue(123456, $dynamicEntityFieldDefinitionTransfer));
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface
     */
    protected function createStringFieldTypeValidator(): DynamicEntityValidatorInterface
    {
        return new StringFieldTypeValidator(
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
