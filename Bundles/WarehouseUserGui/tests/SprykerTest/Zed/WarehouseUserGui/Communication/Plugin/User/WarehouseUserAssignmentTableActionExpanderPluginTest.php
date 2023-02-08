<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\WarehouseUserGui\Communication\Plugin\User;

use Codeception\Test\Unit;
use Orm\Zed\User\Persistence\Map\SpyUserTableMap;
use Spryker\Zed\WarehouseUserGui\Communication\Plugin\User\WarehouseUserAssignmentUserTableActionExpanderPlugin;
use SprykerTest\Zed\WarehouseUserGui\WarehouseUserGuiCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group WarehouseUserGui
 * @group Communication
 * @group Plugin
 * @group User
 * @group WarehouseUserAssignmentTableActionExpanderPluginTest
 * Add your own group annotations below this line
 */
class WarehouseUserAssignmentTableActionExpanderPluginTest extends Unit
{
    /**
     * @var int
     */
    protected const FAKE_USER_ID = 1;

    /**
     * @var string
     */
    protected const FAKE_USER_UUID = 'fake-user-uuid';

    /**
     * @uses \Spryker\Zed\WarehouseUserGui\Communication\Controller\AssignWarehouseController::PARAM_USER_UUID
     *
     * @var string
     */
    protected const PARAM_USER_UUID = 'user-uuid';

    /**
     * @uses \Spryker\Zed\WarehouseUserGui\Communication\Controller\AssignWarehouseController::URL_ASSIGN_WAREHOUSE
     *
     * @var string
     */
    protected const URL_ASSIGN_WAREHOUSE = '/warehouse-user-gui/assign-warehouse';

    /**
     * @var \SprykerTest\Zed\WarehouseUserGui\WarehouseUserGuiCommunicationTester
     */
    protected WarehouseUserGuiCommunicationTester $tester;

    /**
     * @dataProvider testGetActionButtonDefinitionsDataProvider
     *
     * @param array<string, mixed> $user
     * @param list<string> $expectedResult
     *
     * @return void
     */
    public function testGetActionButtonDefinitionsReturnsCorrectResponse(array $user, array $expectedResult): void
    {
        // Arrange
        $warehouseUserLoginRestrictionPlugin = new WarehouseUserAssignmentUserTableActionExpanderPlugin();

        // Act
        $actionButtonTransfers = $warehouseUserLoginRestrictionPlugin->getActionButtonDefinitions($user);

        // Assert
        $this->assertCount(count($expectedResult), $actionButtonTransfers);
        foreach ($expectedResult as $key => $item) {
            $this->assertSame($item, $actionButtonTransfers[$key]->getUrl());
        }
    }

    /**
     * @return array<string, list<mixed>>
     */
    protected function testGetActionButtonDefinitionsDataProvider(): array
    {
        return [
            'User is warehouse user with uuid' => [
                [
                    SpyUserTableMap::COL_ID_USER => static::FAKE_USER_ID,
                    SpyUserTableMap::COL_IS_WAREHOUSE_USER => true,
                    SpyUserTableMap::COL_UUID => static::FAKE_USER_UUID,
                ],
                [
                    static::URL_ASSIGN_WAREHOUSE . '?' . static::PARAM_USER_UUID . '=' . static::FAKE_USER_UUID,
                ],
            ],
            'User is warehouse user without uuid' => [
                [
                    SpyUserTableMap::COL_ID_USER => static::FAKE_USER_ID,
                    SpyUserTableMap::COL_IS_WAREHOUSE_USER => true,
                    SpyUserTableMap::COL_UUID => null,
                ],
                [],
            ],
            'User is not warehouse user with uuid' => [
                [
                    SpyUserTableMap::COL_ID_USER => static::FAKE_USER_ID,
                    SpyUserTableMap::COL_IS_WAREHOUSE_USER => false,
                    SpyUserTableMap::COL_UUID => static::FAKE_USER_UUID,
                ],
                [],
            ],
        ];
    }
}
