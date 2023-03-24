<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Dependency\Facade;

use Generated\Shared\Transfer\PickingListCollectionRequestTransfer;
use Generated\Shared\Transfer\PickingListCollectionResponseTransfer;
use Generated\Shared\Transfer\PickingListCollectionTransfer;
use Generated\Shared\Transfer\PickingListCriteriaTransfer;

class PickingListsBackendApiToPickingListFacadeBridge implements PickingListsBackendApiToPickingListFacadeInterface
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
    public function getPickingListCollection(
        PickingListCriteriaTransfer $pickingListCriteriaTransfer
    ): PickingListCollectionTransfer {
        return $this->pickingListFacade->getPickingListCollection($pickingListCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionRequestTransfer $pickingListCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionResponseTransfer
     */
    public function updatePickingListCollection(
        PickingListCollectionRequestTransfer $pickingListCollectionRequestTransfer
    ): PickingListCollectionResponseTransfer {
        return $this->pickingListFacade->updatePickingListCollection($pickingListCollectionRequestTransfer);
    }
}
