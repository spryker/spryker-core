<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\PickingListsWarehousesBackendResourceRelationship;

use Codeception\Actor;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\PickingListsBackendApiAttributesTransfer;
use Generated\Shared\Transfer\PickingListTransfer;
use Generated\Shared\Transfer\StockTransfer;

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
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(\SprykerTest\Glue\PickingListsWarehousesBackendResourceRelationship\PHPMD)
 */
class PickingListsWarehousesBackendResourceRelationshipTester extends Actor
{
    use _generated\PickingListsWarehousesBackendResourceRelationshipTesterActions;

    /**
     * @uses \Spryker\Shared\PickingList\PickingListConfig::STATUS_READY_FOR_PICKING
     *
     * @var string
     */
    protected const STATUS_READY_FOR_PICKING = 'ready-for-picking';

    /**
     * @uses \Spryker\Glue\PickingListsBackendApi\PickingListsBackendApiConfig::RESOURCE_PICKING_LISTS
     *
     * @var string
     */
    protected const RESOURCE_PICKING_LISTS = 'picking-lists';

    /**
     * @param \Generated\Shared\Transfer\StockTransfer|null $stockTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListTransfer
     */
    public function createPickingList(?StockTransfer $stockTransfer = null): PickingListTransfer
    {
        $stockTransfer = $stockTransfer ?? $this->haveStock();

        $pickingListTransfer = (new PickingListTransfer())
            ->setWarehouse($stockTransfer)
            ->setStatus(static::STATUS_READY_FOR_PICKING);

        return $this->havePickingList($pickingListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResourceTransfer
     */
    public function createPickingListResource(PickingListTransfer $pickingListTransfer): GlueResourceTransfer
    {
        return (new GlueResourceTransfer())
            ->setId($pickingListTransfer->getUuidOrFail())
            ->setType(static::RESOURCE_PICKING_LISTS)
            ->setAttributes(
                (new PickingListsBackendApiAttributesTransfer())->fromArray($pickingListTransfer->toArray(), true),
            );
    }
}
