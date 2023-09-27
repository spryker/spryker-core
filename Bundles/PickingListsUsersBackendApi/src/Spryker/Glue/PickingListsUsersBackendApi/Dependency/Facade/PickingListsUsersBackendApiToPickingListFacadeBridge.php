<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsUsersBackendApi\Dependency\Facade;

use Generated\Shared\Transfer\PickingListCollectionTransfer;
use Generated\Shared\Transfer\PickingListCriteriaTransfer;

class PickingListsUsersBackendApiToPickingListFacadeBridge implements PickingListsUsersBackendApiToPickingListFacadeInterface
{
    /**
     * @var \Spryker\Zed\PickingList\Business\PickingListFacadeInterface
     */
    protected $pickingListFacade;

    /**
     * @param \Spryker\Zed\PickingList\Business\PickingListFacadeInterface $pickingListFacade
     */
    public function __construct($pickingListFacade)
    {
        $this->pickingListFacade = $pickingListFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListCriteriaTransfer $pickingListCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    public function getPickingListCollection(PickingListCriteriaTransfer $pickingListCriteriaTransfer): PickingListCollectionTransfer
    {
        return $this->pickingListFacade->getPickingListCollection($pickingListCriteriaTransfer);
    }
}
