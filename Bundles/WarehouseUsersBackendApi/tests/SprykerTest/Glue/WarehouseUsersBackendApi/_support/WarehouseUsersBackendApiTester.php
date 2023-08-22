<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\WarehouseUsersBackendApi;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentsBackendApiAttributesTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentTransfer;
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
 *
 * @SuppressWarnings(\SprykerTest\Glue\WarehouseUsersBackendApi\PHPMD)
 */
class WarehouseUsersBackendApiTester extends Actor
{
    use _generated\WarehouseUsersBackendApiTesterActions;

    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer
     * @param \Generated\Shared\Transfer\GlueResourceTransfer $warehouseUserAssignmentsResource
     *
     * @return void
     */
    public function assertWarehouseUserAssignmentsResource(
        WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer,
        GlueResourceTransfer $warehouseUserAssignmentsResource
    ): void {
        $this->assertSame($warehouseUserAssignmentTransfer->getUuidOrFail(), $warehouseUserAssignmentsResource->getId());
        $this->assertNotNull($warehouseUserAssignmentsResource->getAttributes());
        $this->assertInstanceOf(WarehouseUserAssignmentsBackendApiAttributesTransfer::class, $warehouseUserAssignmentsResource->getAttributes());

        /** @var \Generated\Shared\Transfer\WarehouseUserAssignmentsBackendApiAttributesTransfer $warehouseUserAssignmentsBackendApiAttributesTransfer */
        $warehouseUserAssignmentsBackendApiAttributesTransfer = $warehouseUserAssignmentsResource->getAttributesOrFail();
        $this->assertSame($warehouseUserAssignmentTransfer->getUserUuidOrFail(), $warehouseUserAssignmentsBackendApiAttributesTransfer->getUserUuid());
        $this->assertSame($warehouseUserAssignmentTransfer->getIsActiveOrFail(), $warehouseUserAssignmentsBackendApiAttributesTransfer->getIsActive());
        $this->assertNotNull($warehouseUserAssignmentTransfer->getWarehouse());
        $this->assertSame($warehouseUserAssignmentTransfer->getWarehouseOrFail()->getUuidOrFail(), $warehouseUserAssignmentTransfer->getWarehouseOrFail()->getUuid());
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     * @param string $uuid
     *
     * @return \Generated\Shared\Transfer\GlueResourceTransfer|null
     */
    public function findGlueResourceByUuid(ArrayObject $glueResourceTransfers, string $uuid): ?GlueResourceTransfer
    {
        foreach ($glueResourceTransfers as $glueResourceTransfer) {
            if ($glueResourceTransfer->getId() === $uuid) {
                return $glueResourceTransfer;
            }
        }

        return null;
    }

    /**
     * @param int $idWarehouseUserAssignment
     *
     * @return void
     */
    public function assertWarehouseUserAssignmentPersisted(int $idWarehouseUserAssignment): void
    {
        $this->assertTrue(
            $this->getWarehouseUserAssignmentQuery()
                ->filterByIdWarehouseUserAssignment($idWarehouseUserAssignment)
                ->exists(),
        );
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
     * @return \Orm\Zed\WarehouseUser\Persistence\SpyWarehouseUserAssignmentQuery
     */
    protected function getWarehouseUserAssignmentQuery(): SpyWarehouseUserAssignmentQuery
    {
        return SpyWarehouseUserAssignmentQuery::create();
    }
}
