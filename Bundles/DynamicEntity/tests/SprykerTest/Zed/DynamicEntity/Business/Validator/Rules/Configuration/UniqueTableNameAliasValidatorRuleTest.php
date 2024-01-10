<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DynamicEntity\Business\Validator\Rules\Configuration;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Spryker\Zed\DynamicEntity\Business\Validator\Rules\Configuration\UniqueTableNameAliasValidatorRule;
use Spryker\Zed\DynamicEntity\Persistence\DynamicEntityRepositoryInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DynamicEntity
 * @group Business
 * @group Validator
 * @group Rules
 * @group Configuration
 * @group UniqueTableNameAliasValidatorRuleTest
 * Add your own group annotations below this line
 */
class UniqueTableNameAliasValidatorRuleTest extends Unit
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
        $validatorRule = new UniqueTableNameAliasValidatorRule($this->getMockBuilder(DynamicEntityRepositoryInterface::class)->getMock());

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
        $dynamicEntityRepositoryMock = $this->getMockBuilder(DynamicEntityRepositoryInterface::class)->getMock();
        $dynamicEntityRepositoryMock->method('getDynamicEntityConfigurationCollectionByTableAliasesOrTableNames')->willReturn(new DynamicEntityConfigurationCollectionTransfer());

        $validatorRule = new UniqueTableNameAliasValidatorRule($dynamicEntityRepositoryMock);
        $dynamicEntityConfigurationCollectionRequestTransfer = $this->tester->createDynamicEntityConfigurationCollectionRequestTransfer();

        // Act
        $errorCollectionTransfer = $validatorRule->validate($dynamicEntityConfigurationCollectionRequestTransfer->getDynamicEntityConfigurations());

        // Assert
        $this->assertCount(0, $errorCollectionTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testValidateCreateCollectionWillReturnWithError(): void
    {
        // Arrange
        $dynamicEntityRepositoryMock = $this->getMockBuilder(DynamicEntityRepositoryInterface::class)->getMock();
        $dynamicEntityRepositoryMock->method('getDynamicEntityConfigurationCollectionByTableAliasesOrTableNames')->willReturn(
            (new DynamicEntityConfigurationCollectionTransfer())
                ->addDynamicEntityConfiguration((new DynamicEntityConfigurationTransfer())
                    ->setIdDynamicEntityConfiguration(10)
                    ->setTableAlias('test-table')
                    ->setTableName('spy_test_table')),
        );

        $validatorRule = new UniqueTableNameAliasValidatorRule($dynamicEntityRepositoryMock);
        $dynamicEntityConfigurationCollectionRequestTransfer = $this->tester->createDynamicEntityConfigurationCollectionRequestTransfer();

        // Act
        $errorCollectionTransfer = $validatorRule->validate($dynamicEntityConfigurationCollectionRequestTransfer->getDynamicEntityConfigurations());

        // Assert
        $this->assertCount(1, $errorCollectionTransfer->getErrors());
        $errorTransfer = $errorCollectionTransfer->getErrors()[0];
        $this->assertSame('Table name or table alias is not unique for dynamic entity. Table: spy_test_table', $errorTransfer->getMessage());
        $this->assertSame('spy_test_table', $errorTransfer->getEntityIdentifier());
    }

    /**
     * @return void
     */
    public function testValidateCreateCollectionWillReturnWithErrors(): void
    {
        // Arrange
        $dynamicEntityRepositoryMock = $this->getMockBuilder(DynamicEntityRepositoryInterface::class)->getMock();
        $dynamicEntityRepositoryMock->method('getDynamicEntityConfigurationCollectionByTableAliasesOrTableNames')->willReturn(
            (new DynamicEntityConfigurationCollectionTransfer())
                ->addDynamicEntityConfiguration((new DynamicEntityConfigurationTransfer())
                    ->setIdDynamicEntityConfiguration(10)
                    ->setTableAlias('test-endpoint')
                    ->setTableName('spy_my_table'))
                ->addDynamicEntityConfiguration((new DynamicEntityConfigurationTransfer())
                    ->setIdDynamicEntityConfiguration(11)
                    ->setTableAlias('my-endpoint')
                    ->setTableName('spy_test_table')),
        );

        $validatorRule = new UniqueTableNameAliasValidatorRule($dynamicEntityRepositoryMock);
        $dynamicEntityConfigurationCollectionRequestTransfer = $this->tester->createDynamicEntityConfigurationCollectionRequestTransfer();

        // Act
        $errorCollectionTransfer = $validatorRule->validate($dynamicEntityConfigurationCollectionRequestTransfer->getDynamicEntityConfigurations());

        // Assert
        $this->assertCount(2, $errorCollectionTransfer->getErrors());
        $errorTransfer = $errorCollectionTransfer->getErrors()[0];
        $this->assertSame('Table name or table alias is not unique for dynamic entity. Table: spy_my_table', $errorTransfer->getMessage());
        $this->assertSame('spy_my_table', $errorTransfer->getEntityIdentifier());
        $errorTransfer = $errorCollectionTransfer->getErrors()[1];
        $this->assertSame('Table name or table alias is not unique for dynamic entity. Table: spy_test_table', $errorTransfer->getMessage());
        $this->assertSame('spy_test_table', $errorTransfer->getEntityIdentifier());
    }
}
