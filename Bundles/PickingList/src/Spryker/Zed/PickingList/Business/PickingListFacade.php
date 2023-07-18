<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business;

use Generated\Shared\Transfer\GeneratePickingListsRequestTransfer;
use Generated\Shared\Transfer\PickingFinishedRequestTransfer;
use Generated\Shared\Transfer\PickingFinishedResponseTransfer;
use Generated\Shared\Transfer\PickingListCollectionRequestTransfer;
use Generated\Shared\Transfer\PickingListCollectionResponseTransfer;
use Generated\Shared\Transfer\PickingListCollectionTransfer;
use Generated\Shared\Transfer\PickingListCriteriaTransfer;
use Generated\Shared\Transfer\PickingListGenerationFinishedRequestTransfer;
use Generated\Shared\Transfer\PickingListGenerationFinishedResponseTransfer;
use Generated\Shared\Transfer\PickingStartedRequestTransfer;
use Generated\Shared\Transfer\PickingStartedResponseTransfer;
use Generated\Shared\Transfer\UserCollectionTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\PickingList\Business\PickingListBusinessFactory getFactory()
 * @method \Spryker\Zed\PickingList\Persistence\PickingListRepositoryInterface getRepository()
 * @method \Spryker\Zed\PickingList\Persistence\PickingListEntityManagerInterface getEntityManager()
 */
class PickingListFacade extends AbstractFacade implements PickingListFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PickingListCriteriaTransfer $pickingListCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    public function getPickingListCollection(
        PickingListCriteriaTransfer $pickingListCriteriaTransfer
    ): PickingListCollectionTransfer {
        return $this->getFactory()
            ->createPickingListReader()
            ->getPickingListCollection($pickingListCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PickingListCollectionRequestTransfer $pickingListCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionResponseTransfer
     */
    public function createPickingListCollection(
        PickingListCollectionRequestTransfer $pickingListCollectionRequestTransfer
    ): PickingListCollectionResponseTransfer {
        return $this->getFactory()
            ->createPickingListCreator()
            ->createPickingListCollection($pickingListCollectionRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PickingListCollectionRequestTransfer $pickingListCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionResponseTransfer
     */
    public function updatePickingListCollection(
        PickingListCollectionRequestTransfer $pickingListCollectionRequestTransfer
    ): PickingListCollectionResponseTransfer {
        return $this->getFactory()
            ->createPickingListUpdater()
            ->updatePickingListCollection($pickingListCollectionRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GeneratePickingListsRequestTransfer $generatePickingListsRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionResponseTransfer
     */
    public function generatePickingLists(
        GeneratePickingListsRequestTransfer $generatePickingListsRequestTransfer
    ): PickingListCollectionResponseTransfer {
        return $this->getFactory()
            ->createPickingListGenerator()
            ->generatePickingLists($generatePickingListsRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PickingListGenerationFinishedRequestTransfer $pickingListGenerationFinishedRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListGenerationFinishedResponseTransfer
     */
    public function isPickingListGenerationFinished(
        PickingListGenerationFinishedRequestTransfer $pickingListGenerationFinishedRequestTransfer
    ): PickingListGenerationFinishedResponseTransfer {
        return $this->getFactory()
            ->createPickingListGenerationFinishedValidator()
            ->isPickingListGenerationFinished($pickingListGenerationFinishedRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PickingStartedRequestTransfer $pickingStartedRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PickingStartedResponseTransfer
     */
    public function isPickingStarted(
        PickingStartedRequestTransfer $pickingStartedRequestTransfer
    ): PickingStartedResponseTransfer {
        return $this->getFactory()
            ->createPickingListPickingStartedValidator()
            ->isPickingStarted($pickingStartedRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PickingFinishedRequestTransfer $pickingFinishedRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PickingFinishedResponseTransfer
     */
    public function isPickingFinished(
        PickingFinishedRequestTransfer $pickingFinishedRequestTransfer
    ): PickingFinishedResponseTransfer {
        return $this->getFactory()
            ->createPickingListPickingFinishedValidator()
            ->isPickingFinished($pickingFinishedRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserCollectionTransfer $userCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\UserCollectionTransfer
     */
    public function unassignPickingListsFromUsers(UserCollectionTransfer $userCollectionTransfer): UserCollectionTransfer
    {
        return $this->getFactory()
            ->createPickingListUserAssigner()
            ->unassignPickingListsFromUsers($userCollectionTransfer);
    }
}
