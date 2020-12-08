<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Transfer\Business\XmlValidator;

use Codeception\Test\Unit;
use Spryker\Zed\Transfer\Business\XmlValidator\XmlXsdSchemaValidator;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Transfer
 * @group Business
 * @group XmlValidator
 * @group XmlXsdSchemaValidatorTest
 * Add your own group annotations below this line
 */
class XmlXsdSchemaValidatorTest extends Unit
{
    /**
     * @return void
     */
    public function testCanLogError(): void
    {
        // Arrange
        $xmlValidator = new XmlXsdSchemaValidator();
        $schemaFilePath = codecept_data_dir('XmlValidator/transfer-01.xsd');
        $transferDefinitionFilePath = codecept_data_dir('XmlValidator/error.transfer.xml');

        // Act
        $xmlValidator->validate($transferDefinitionFilePath, $schemaFilePath);

        // Assert
        $this->assertFalse($xmlValidator->isValid());
        $this->assertCount(1, $xmlValidator->getErrors());
        $this->assertStringContainsString("The attribute 'foo' is not allowed.", $xmlValidator->getErrors()[0]);
        $this->assertStringContainsString('error.transfer.xml', $xmlValidator->getErrors()[0]);
    }

    /**
     * @return void
     */
    public function testCanLogExceptions(): void
    {
        // Arrange
        $xmlValidator = new XmlXsdSchemaValidator();
        $schemaFilePath = codecept_data_dir('XmlValidator/transfer-01.xsd');
        $transferDefinitionFilePath = codecept_data_dir('XmlValidator/exception.transfer.xml');

        // Act
        $xmlValidator->validate($transferDefinitionFilePath, $schemaFilePath);

        // Assert
        $this->assertFalse($xmlValidator->isValid());
        $this->assertCount(1, $xmlValidator->getErrors());
        $this->assertStringContainsString(
            "The attribute '{www.w3.org/2001/XMLSchema-instance}schemaLocation' is not allowed.",
            $xmlValidator->getErrors()[0]
        );
        $this->assertStringContainsString('exception.transfer.xml', $xmlValidator->getErrors()[0]);
    }

    /**
     * @return void
     */
    public function testCanResetErrorLog(): void
    {
        // Arrange
        $xmlValidator = new XmlXsdSchemaValidator();
        $schemaFilePath = codecept_data_dir('XmlValidator/transfer-01.xsd');
        $transferDefinitionFilePath = codecept_data_dir('XmlValidator/error.transfer.xml');

        // Act
        $xmlValidator->validate($transferDefinitionFilePath, $schemaFilePath);
        $xmlValidator->validate($transferDefinitionFilePath, $schemaFilePath);

        // Assert
        $this->assertCount(1, $xmlValidator->getErrors());
    }
}
