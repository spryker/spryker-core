<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DynamicEntity\Business\Validator\Field\Type;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;
use Spryker\Zed\DynamicEntity\Business\Validator\Field\Type\BooleanFieldTypeValidator;

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
 * @group BooleanFieldTypeValidatorTest
 * Add your own group annotations below this line
 */
class BooleanFieldTypeValidatorTest extends Unit
{
    /**
     * @return void
     */
    public function testWillReturnValidatorType(): void
    {
        $validator = new BooleanFieldTypeValidator();

        $this->assertSame('boolean', $validator->getType());
    }

    /**
     * @return void
     */
    public function testValidTypeWillReturnTrue(): void
    {
        $validator = new BooleanFieldTypeValidator();

        $this->assertTrue($validator->isValidType(true));
        $this->assertTrue($validator->isValidType(false));
    }

    /**
     * @return void
     */
    public function testValidTypeWillReturnFalse(): void
    {
        $validator = new BooleanFieldTypeValidator();

        $this->assertFalse($validator->isValidType(1));
        $this->assertFalse($validator->isValidType(0));
        $this->assertFalse($validator->isValidType('1'));
        $this->assertFalse($validator->isValidType('0'));
    }

    /**
     * @return void
     */
    public function testValidValueWillReturnTrue(): void
    {
        // Arrange
        $validator = new BooleanFieldTypeValidator();
        $dynamicEntityFieldDefinitionTransfer = new DynamicEntityFieldDefinitionTransfer();

        // Act & Assert
        $this->assertTrue($validator->isValidValue(true, $dynamicEntityFieldDefinitionTransfer));
        $this->assertTrue($validator->isValidValue(false, $dynamicEntityFieldDefinitionTransfer));
    }

    /**
     * @return void
     */
    public function testValidValueWillReturnFalse(): void
    {
        // Arrange
        $validator = new BooleanFieldTypeValidator();
        $dynamicEntityFieldDefinitionTransfer = new DynamicEntityFieldDefinitionTransfer();

        // Act & Assert
        $this->assertFalse($validator->isValidValue(1, $dynamicEntityFieldDefinitionTransfer));
        $this->assertFalse($validator->isValidValue(0, $dynamicEntityFieldDefinitionTransfer));
        $this->assertFalse($validator->isValidValue('1', $dynamicEntityFieldDefinitionTransfer));
        $this->assertFalse($validator->isValidValue('0', $dynamicEntityFieldDefinitionTransfer));
    }
}
