<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\UtilUuidGenerator\Business\UtilUuidGeneratorFacade;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group UtilUuidGenerator
 * @group Business
 * @group UtilUuidGeneratorFacade
 * @group Facade
 * @group UtilUuidGeneratorFacadeTest
 * Add your own group annotations below this line
 */
class UtilUuidGeneratorFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\UtilUuidGenerator\UtilUuidGeneratorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGenerateUuidsByTableName(): void
    {
        // Act
        $updatedRecordCount = $this->tester->getFacade()->generateUuids('Tax.spy_tax_set');

        // Assert
        $this->assertEquals(0, $updatedRecordCount);
    }

    /**
     * @return void
     */
    public function testGenerateUuidsWithWrongTableName(): void
    {
        // Assert
        $this->expectException('Exception');
        $this->expectExceptionMessage("Query 'Orm\Zed\Tax\Persistence\SpyWrongTableNameQuery' not found.");

        // Act
        $this->tester->getFacade()->generateUuids('Tax.spy_wrong_table_name');
    }

    /**
     * @return void
     */
    public function testGenerateUuidsWithoutUuidField(): void
    {
        // Act
        $updatedRecordCount = $this->tester->getFacade()->generateUuids('Customer.spy_customer');

        // Assert
        $this->assertEquals(0, $updatedRecordCount);
    }
}
