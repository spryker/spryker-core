<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\WarehouseUser;

use Codeception\Actor;
use Generated\Shared\DataBuilder\StockBuilder;
use Generated\Shared\DataBuilder\WarehouseUserAssignmentBuilder;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentTransfer;
use Orm\Zed\WarehouseUser\Persistence\SpyWarehouseUserAssignment;
use Orm\Zed\WarehouseUser\Persistence\SpyWarehouseUserAssignmentQuery;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 * @method \Spryker\Zed\WarehouseUser\Business\WarehouseUserFacadeInterface getFacade(?string $moduleName = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class WarehouseUserBusinessTester extends Actor
{
    use _generated\WarehouseUserBusinessTesterActions;

    /**
     * @var string
     */
    protected const NON_EXISTING_WAREHOUSE_USER_ASSIGNMENT_UUID = 'non_existing_warehouse_user_assignment_uuid';

    /**
     * @var string
     */
    protected const NON_EXISTING_STOCK_UUID = 'non_existing_stock_uuid';

    /**
     * @var string
     */
    protected const NON_EXISTING_WAREHOUSE_USER_ASSIGNMENT_ID = -1;

    /**
     * @var string
     */
    protected const NON_EXISTING_STOCK_ID = -1;

    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer $expectedWarehouseUserAssignment
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer $actualWarehouseUserAssignmentTransfer
     *
     * @return void
     */
    public function assertSameWarehouseUserAssignment(
        WarehouseUserAssignmentTransfer $expectedWarehouseUserAssignment,
        WarehouseUserAssignmentTransfer $actualWarehouseUserAssignmentTransfer
    ): void {
        $this->assertSame($expectedWarehouseUserAssignment->getIdWarehouseUserAssignmentOrFail(), $actualWarehouseUserAssignmentTransfer->getIdWarehouseUserAssignment());
        $this->assertSame($expectedWarehouseUserAssignment->getUuid(), $actualWarehouseUserAssignmentTransfer->getUuid());
        $this->assertSame($expectedWarehouseUserAssignment->getUserUuidOrFail(), $actualWarehouseUserAssignmentTransfer->getUserUuid());
        $this->assertSame($expectedWarehouseUserAssignment->getIsActiveOrFail(), $actualWarehouseUserAssignmentTransfer->getIsActive());
        $this->assertNotNull($actualWarehouseUserAssignmentTransfer->getWarehouse());
        $this->assertSame($expectedWarehouseUserAssignment->getWarehouseOrFail()->getIdStockOrFail(), $actualWarehouseUserAssignmentTransfer->getWarehouseOrFail()->getIdStock());
    }

    /**
     * @return \Generated\Shared\Transfer\StockTransfer
     */
    public function getNotExistingStockTransfer(): StockTransfer
    {
        return (new StockBuilder([
            StockTransfer::ID_STOCK => static::NON_EXISTING_STOCK_ID,
            StockTransfer::UUID => static::NON_EXISTING_STOCK_UUID,
        ]))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer
     */
    public function getNotExistingWarehouseUserAssignmentTransfer(): WarehouseUserAssignmentTransfer
    {
        $warehouseUserAssignmentTransfer = (new WarehouseUserAssignmentBuilder([
            WarehouseUserAssignmentTransfer::USER_UUID => $this->haveUser()->getUuidOrFail(),
            WarehouseUserAssignmentTransfer::UUID => static::NON_EXISTING_WAREHOUSE_USER_ASSIGNMENT_UUID,
            WarehouseUserAssignmentTransfer::ID_WAREHOUSE_USER_ASSIGNMENT => static::NON_EXISTING_WAREHOUSE_USER_ASSIGNMENT_ID,
        ]))->build();
        $warehouseUserAssignmentTransfer->setWarehouse($this->haveStock());

        return $warehouseUserAssignmentTransfer;
    }

    /**
     * @param int $idWarehouseUserAssignment
     *
     * @return void
     */
    public function assertWarehouseUserAssignmentNotPersisted(int $idWarehouseUserAssignment): void
    {
        $this->assertSame(
            0,
            $this->getWarehouseUserAssignmentQuery()
                ->filterByIdWarehouseUserAssignment($idWarehouseUserAssignment)
                ->count(),
        );
    }

    /**
     * @param int $idWarehouseUserAssignment
     *
     * @return \Orm\Zed\WarehouseUser\Persistence\SpyWarehouseUserAssignment|null
     */
    public function findWarehouseUserAssignment(int $idWarehouseUserAssignment): ?SpyWarehouseUserAssignment
    {
        return $this->getWarehouseUserAssignmentQuery()
            ->filterByIdWarehouseUserAssignment($idWarehouseUserAssignment)
            ->findOne();
    }

    /**
     * @return \Orm\Zed\WarehouseUser\Persistence\SpyWarehouseUserAssignmentQuery
     */
    protected function getWarehouseUserAssignmentQuery(): SpyWarehouseUserAssignmentQuery
    {
        return SpyWarehouseUserAssignmentQuery::create();
    }
}
