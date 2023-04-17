<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Communication\Plugin\User;

use Generated\Shared\Transfer\UserCollectionTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\UserExtension\Dependency\Plugin\UserPostSavePluginInterface;

/**
 * @method \Spryker\Zed\PickingList\Business\PickingListFacadeInterface getFacade()
 * @method \Spryker\Zed\PickingList\PickingListConfig getConfig()
 * @method \Spryker\Zed\PickingList\Communication\PickingListCommunicationFactory getFactory()
 */
class UnassignPickingListUserPostSavePlugin extends AbstractPlugin implements UserPostSavePluginInterface
{
    /**
     * {@inheritDoc}
     * - Does nothing if `UserTransfer.isWarehouseUser` transfer property is not set to `true`.
     * - Does nothing if `UserTransfer.status` property is not `blocked` or `deleted`.
     * - Requires `UserTransfer.uuid` transfer property to be set.
     * - Finds picking lists assigned to provided user by `UserTransfer.uuid` transfer property.
     * - Removes user assignment from found picking lists.
     * - Persists updated picking lists.
     * - Returns unmodified `UserTransfer` object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function postSave(UserTransfer $userTransfer): UserTransfer
    {
        $userCollectionTransfer = (new UserCollectionTransfer())->addUser($userTransfer);
        $userCollectionTransfer = $this->getFacade()->unassignPickingListsFromUsers($userCollectionTransfer);

        return $userCollectionTransfer->getUsers()->getIterator()->current();
    }
}
