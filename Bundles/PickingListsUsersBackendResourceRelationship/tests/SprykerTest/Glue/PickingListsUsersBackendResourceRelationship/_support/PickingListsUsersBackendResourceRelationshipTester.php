<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\PickingListsUsersBackendResourceRelationship;

use Codeception\Actor;
use Generated\Shared\DataBuilder\PickingListBuilder;
use Generated\Shared\Transfer\PickingListTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\PickingList\Business\PickingListFacadeInterface;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(\SprykerTest\Glue\PickingListsUsersBackendResourceRelationship\PHPMD)
 */
class PickingListsUsersBackendResourceRelationshipTester extends Actor
{
    use _generated\PickingListsUsersBackendResourceRelationshipTesterActions;

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListTransfer
     */
    public function createPickingList(UserTransfer $userTransfer): PickingListTransfer
    {
        $stockTransfer = $this->haveStock();
        $this->haveWarehouseUserAssignment($userTransfer, $stockTransfer);

        $pickingListTransfer = (new PickingListBuilder([
            PickingListTransfer::WAREHOUSE => $stockTransfer->toArray(),
        ]))->build();

        return $this->havePickingList($pickingListTransfer);
    }

    /**
     * @return \Spryker\Zed\PickingList\Business\PickingListFacadeInterface
     */
    public function getPickingListFacade(): PickingListFacadeInterface
    {
        return $this->getLocator()->pickingList()->facade();
    }
}
