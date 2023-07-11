<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DynamicEntity\Business\Validator\Field\Type;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldValidationTransfer;
use Spryker\Zed\DynamicEntity\Business\Validator\Field\Type\DecimalFieldTypeValidator;

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
 * @group DecimalFieldTypeValidatorTest
 * Add your own group annotations below this line
 */
class DecimalFieldTypeValidatorTest extends Unit
{
    /**
     * @return void
     */
    public function testWillReturnValidatorType(): void
    {
        // Arrange
        $validator = new DecimalFieldTypeValidator();

        // Act & Assert
        $this->assertSame('decimal', $validator->getType());
    }

    /**
     * @return void
     */
    public function testValidTypeWillReturnTrue(): void
    {
        // Arrange
        $validator = new DecimalFieldTypeValidator();

        // Act & Assert
        $this->assertTrue($validator->isValidType('0.0'));
        $this->assertTrue($validator->isValidType('-1.1'));
        $this->assertTrue($validator->isValidType('9999999'));
        $this->assertTrue($validator->isValidType(1233));
    }

    /**
     * @return void
     */
    public function testValidTypeWillReturnFalse(): void
    {
        // Arrange
        $validator = new DecimalFieldTypeValidator();

        // Act & Assert
        $this->assertFalse($validator->isValidType('string'));
        $this->assertFalse($validator->isValidType(false));
        $this->assertFalse($validator->isValidType('string.9999999'));
    }

    /**
     * @return void
     */
    public function testValidValueWithValidationRulesWillReturnTrue(): void
    {
        // Arrange
        $validator = new DecimalFieldTypeValidator();
        $dynamicEntityFieldDefinitionTransfer = (new DynamicEntityFieldDefinitionTransfer())->setValidation(
            (new DynamicEntityFieldValidationTransfer())->setPrecision(5)->setScale(2),
        );

        // Act & Assert
        $this->assertTrue($validator->isValidValue(0, $dynamicEntityFieldDefinitionTransfer));
        $this->assertTrue($validator->isValidValue('1', $dynamicEntityFieldDefinitionTransfer));
        $this->assertTrue($validator->isValidValue('0', $dynamicEntityFieldDefinitionTransfer));
        $this->assertTrue($validator->isValidValue('123.55', $dynamicEntityFieldDefinitionTransfer));
        $this->assertTrue($validator->isValidValue('0.0000', $dynamicEntityFieldDefinitionTransfer));
    }
}
