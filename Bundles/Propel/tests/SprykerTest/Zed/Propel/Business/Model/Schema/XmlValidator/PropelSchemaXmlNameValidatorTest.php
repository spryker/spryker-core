<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Propel\Business\Model\Schema\XmlValidator;

use Codeception\Test\Unit;
use Spryker\Zed\Propel\Business\Model\PropelSchemaFinder;
use Spryker\Zed\Propel\Business\Model\PropelSchemaFinderInterface;
use Spryker\Zed\Propel\Business\Model\Schema\XmlValidator\PropelSchemaXmlNameValidator;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Propel
 * @group Business
 * @group Model
 * @group Schema
 * @group XmlValidator
 * @group PropelSchemaXmlNameValidatorTest
 * Add your own group annotations below this line
 */
class PropelSchemaXmlNameValidatorTest extends Unit
{
    /**
     * @return void
     */
    public function testValidateShouldReturnTransferWithoutErrorsWhenNamesHaveProperLengthAndTableNamesAreUnique(): void
    {
        $this->assertTrue($this->executeXmlValidation(['Valid']));
    }

    /**
     * @dataProvider invalidXmlValidationDataProvider
     *
     * @param array $dirPattern
     *
     * @return void
     */
    public function testValidateShouldReturnTransferWithErrors(array $dirPattern): void
    {
        $this->assertFalse($this->executeXmlValidation($dirPattern));
    }

    /**
     * @param array $dirPattern
     *
     * @return bool
     */
    protected function executeXmlValidation(array $dirPattern): bool
    {
        $propelSchemaValidator = new PropelSchemaXmlNameValidator(
            $this->getPropelSchemaFinder($dirPattern)
        );
        $schemaValidationTransfer = $propelSchemaValidator->validate();

        return (bool)$schemaValidationTransfer->getIsSuccess();
    }

    /**
     * @return array
     */
    public function invalidXmlValidationDataProvider(): array
    {
        return [
            [['Invalid', 'TooLongTableName']],
            [['Invalid', 'TooLongTableIndexName']],
            [['Invalid', 'TooLongTableColumnName']],
            [['Invalid', 'TooLongTableUniqueName']],
            [['Invalid', 'TooLongTableForeignKeyName']],
            [['Invalid', 'TooLongTableForeignKeyReferenceLocal']],
            [['Invalid', 'TooLongTableIdMethodParameterValue']],
        ];
    }

    /**
     * @param array $dirPattern
     *
     * @return \Spryker\Zed\Propel\Business\Model\PropelSchemaFinderInterface
     */
    protected function getPropelSchemaFinder(array $dirPattern): PropelSchemaFinderInterface
    {
        $pathParts = array_merge([
            __DIR__,
            'Fixtures',
        ], $dirPattern);

        return new PropelSchemaFinder([
            implode(DIRECTORY_SEPARATOR, $pathParts),
        ]);
    }
}
