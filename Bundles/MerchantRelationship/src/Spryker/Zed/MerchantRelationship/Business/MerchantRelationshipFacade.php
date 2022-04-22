<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business;

use Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationshipFilterTransfer;
use Generated\Shared\Transfer\MerchantRelationshipRequestTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantRelationship\Business\MerchantRelationshipBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipEntityManagerInterface getEntityManager()
 */
class MerchantRelationshipFacade extends AbstractFacade implements MerchantRelationshipFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer Deprecated: Use {@link \Generated\Shared\Transfer\MerchantRelationshipRequestTransfer} instead.
     * @param \Generated\Shared\Transfer\MerchantRelationshipRequestTransfer|null $merchantRelationshipRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer|\Generated\Shared\Transfer\MerchantRelationshipResponseTransfer
     */
    public function createMerchantRelationship(
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        ?MerchantRelationshipRequestTransfer $merchantRelationshipRequestTransfer = null
    ) {
        if ($merchantRelationshipRequestTransfer === null) {
            trigger_error('[Spryker/MerchantRelationship] Pass the $merchantRelationshipRequestTransfer parameter for the forward compatibility with next major version.', E_USER_DEPRECATED);
        }

        return $this->getFactory()
            ->createMerchantRelationshipCreator()
            ->create($merchantRelationshipTransfer, $merchantRelationshipRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer Deprecated: Use {@link \Generated\Shared\Transfer\MerchantRelationshipRequestTransfer} instead.
     * @param \Generated\Shared\Transfer\MerchantRelationshipRequestTransfer|null $merchantRelationshipRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer|\Generated\Shared\Transfer\MerchantRelationshipResponseTransfer
     */
    public function updateMerchantRelationship(
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        ?MerchantRelationshipRequestTransfer $merchantRelationshipRequestTransfer = null
    ) {
        if ($merchantRelationshipRequestTransfer === null) {
            trigger_error('[Spryker/MerchantRelationship] Pass the $merchantRelationshipRequestTransfer parameter for the forward compatibility with next major version.', E_USER_DEPRECATED);
        }

        return $this->getFactory()
            ->createMerchantRelationshipUpdater()
            ->update($merchantRelationshipTransfer, $merchantRelationshipRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer Deprecated: Use {@link \Generated\Shared\Transfer\MerchantRelationshipRequestTransfer} instead.
     * @param \Generated\Shared\Transfer\MerchantRelationshipRequestTransfer|null $merchantRelationshipRequestTransfer
     *
     * @return void
     */
    public function deleteMerchantRelationship(
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        ?MerchantRelationshipRequestTransfer $merchantRelationshipRequestTransfer = null
    ): void {
        if ($merchantRelationshipRequestTransfer === null) {
            trigger_error('[Spryker/MerchantRelationship] Pass the $merchantRelationshipRequestTransfer parameter for the forward compatibility with next major version.', E_USER_DEPRECATED);
        }

        $this->getFactory()
            ->createMerchantRelationshipDeleter()
            ->delete($merchantRelationshipTransfer, $merchantRelationshipRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function getMerchantRelationshipById(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipTransfer
    {
        return $this->getFactory()
            ->createMerchantRelationshipReader()
            ->getMerchantRelationshipById($merchantRelationshipTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer|null
     */
    public function findMerchantRelationshipByKey(MerchantRelationshipTransfer $merchantRelationshipTransfer): ?MerchantRelationshipTransfer
    {
        return $this->getFactory()
            ->createMerchantRelationshipReader()
            ->findMerchantRelationshipByKey($merchantRelationshipTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipFilterTransfer|null $merchantRelationshipFilterTransfer Deprecated: Use {@link \Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer} instead.
     * @param \Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer|null $merchantRelationshipCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer|array<\Generated\Shared\Transfer\MerchantRelationshipTransfer>
     */
    public function getMerchantRelationshipCollection(
        ?MerchantRelationshipFilterTransfer $merchantRelationshipFilterTransfer = null,
        ?MerchantRelationshipCriteriaTransfer $merchantRelationshipCriteriaTransfer = null
    ) {
        if ($merchantRelationshipCriteriaTransfer === null) {
            trigger_error('[Spryker/MerchantRelationship] Pass the $merchantRelationshipCriteriaTransfer parameter for the forward compatibility with next major version.', E_USER_DEPRECATED);
        }

        return $this->getFactory()
            ->createMerchantRelationshipReader()
            ->getMerchantRelationshipCollection(
                $merchantRelationshipFilterTransfer,
                $merchantRelationshipCriteriaTransfer,
            );
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer|null
     */
    public function findMerchantRelationshipById(MerchantRelationshipTransfer $merchantRelationshipTransfer): ?MerchantRelationshipTransfer
    {
        return $this->getFactory()
            ->createMerchantRelationshipReader()
            ->findMerchantRelationshipById($merchantRelationshipTransfer);
    }
}
