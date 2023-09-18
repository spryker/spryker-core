<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Business;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantProfileCollectionTransfer;
use Generated\Shared\Transfer\MerchantProfileCriteriaTransfer;
use Generated\Shared\Transfer\MerchantProfileTransfer;
use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantProfile\Business\MerchantProfileBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantProfile\Persistence\MerchantProfileEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantProfile\Persistence\MerchantProfileRepositoryInterface getRepository()
 */
class MerchantProfileFacade extends AbstractFacade implements MerchantProfileFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    public function createMerchantProfile(MerchantProfileTransfer $merchantProfileTransfer): MerchantProfileTransfer
    {
        return $this->getFactory()
            ->createMerchantProfileWriter()
            ->create($merchantProfileTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    public function updateMerchantProfile(MerchantProfileTransfer $merchantProfileTransfer): MerchantProfileTransfer
    {
        return $this->getFactory()
            ->createMerchantProfileWriter()
            ->update($merchantProfileTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantProfileCriteriaTransfer $merchantProfileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer|null
     */
    public function findOne(MerchantProfileCriteriaTransfer $merchantProfileCriteriaTransfer): ?MerchantProfileTransfer
    {
        return $this->getFactory()
            ->createMerchantProfileReader()
            ->findOne($merchantProfileCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantProfileCriteriaTransfer $merchantProfileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileCollectionTransfer
     */
    public function get(MerchantProfileCriteriaTransfer $merchantProfileCriteriaTransfer): MerchantProfileCollectionTransfer
    {
        return $this->getFactory()
            ->createMerchantProfileReader()
            ->get($merchantProfileCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function postUpdateMerchant(MerchantTransfer $merchantTransfer): MerchantResponseTransfer
    {
        return $this->getFactory()
            ->createMerchantProfileWriter()
            ->postUpdateMerchant($merchantTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateOrderMerchants(OrderTransfer $orderTransfer): OrderTransfer
    {
        return $this->getFactory()->createMerchantDataOrderHydrate()->hydrateOrderWithMerchantData($orderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function expandQuoteItemsWithMerchantProfileAddress(CalculableObjectTransfer $quoteTransfer): CalculableObjectTransfer
    {
        return $this->getFactory()->createMerchantProfileItemExpander()->expand($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderItemsWithMerchantProfileAddress(OrderTransfer $orderTransfer): OrderTransfer
    {
        return $this->getFactory()->createMerchantProfileItemExpander()->expand($orderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCollectionTransfer $merchantCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function expandMerchantCollectionWithMerchantProfile(
        MerchantCollectionTransfer $merchantCollectionTransfer
    ): MerchantCollectionTransfer {
        return $this->getFactory()
            ->createMerchantProfileMerchantExpander()
            ->expand($merchantCollectionTransfer);
    }
}
