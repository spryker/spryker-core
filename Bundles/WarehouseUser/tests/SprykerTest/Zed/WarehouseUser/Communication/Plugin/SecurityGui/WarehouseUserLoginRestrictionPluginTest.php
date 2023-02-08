<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\WarehouseUser\Communication\Plugin\SecurityGui;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\UserBuilder;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\WarehouseUser\Communication\Plugin\SecurityGui\WarehouseUserLoginRestrictionPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group WarehouseUser
 * @group Communication
 * @group Plugin
 * @group SecurityGui
 * @group WarehouseUserLoginRestrictionPluginTest
 * Add your own group annotations below this line
 */
class WarehouseUserLoginRestrictionPluginTest extends Unit
{
    /**
     * @dataProvider testIsRestrictedReturnsCorrectResponseDataProvider
     *
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param bool $expectedResult
     *
     * @return void
     */
    public function testIsRestrictedReturnsCorrectResponse(UserTransfer $userTransfer, bool $expectedResult): void
    {
        // Arrange
        $warehouseUserLoginRestrictionPlugin = new WarehouseUserLoginRestrictionPlugin();

        // Act
        $result = $warehouseUserLoginRestrictionPlugin->isRestricted($userTransfer);

        // Assert
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @return array<string, list<mixed>>
     */
    protected function testIsRestrictedReturnsCorrectResponseDataProvider(): array
    {
        return [
            'User is warehouse user' => [(new UserBuilder([UserTransfer::IS_WAREHOUSE_USER => true]))->build(), true],
            'User is not warehouse user' => [(new UserBuilder([UserTransfer::IS_WAREHOUSE_USER => false]))->build(), false],
            'Property isWarehouseUser not defined' => [(new UserBuilder([UserTransfer::IS_WAREHOUSE_USER => null]))->build(), false],
        ];
    }
}
