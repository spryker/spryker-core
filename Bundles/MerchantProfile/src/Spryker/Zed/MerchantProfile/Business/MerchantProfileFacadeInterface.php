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

interface MerchantProfileFacadeInterface
{
    /**
     * Specification:
     * - Saves MerchantProfile glossary attributes.
     * - Generates MerchantProfile glossary keys.
     * - Creates merchant profile data provided by MerchantTransfer.
     * - Returns created MerchantProfileTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    public function createMerchantProfile(MerchantProfileTransfer $merchantProfileTransfer): MerchantProfileTransfer;

    /**
     * Specification:
     * - Saves MerchantProfile glossary attributes.
     * - Generates MerchantProfile glossary keys if doesn't exist.
     * - Updates merchant profile data provided by MerchantTransfer.
     * - Returns updated MerchantProfileTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    public function updateMerchantProfile(MerchantProfileTransfer $merchantProfileTransfer): MerchantProfileTransfer;

    /**
     * Specification:
     * - Finds merchant profile by Criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantProfileCriteriaTransfer $merchantProfileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer|null
     */
    public function findOne(MerchantProfileCriteriaTransfer $merchantProfileCriteriaTransfer): ?MerchantProfileTransfer;

    /**
     * Specification:
     * - Finds merchant profiles by Criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantProfileCriteriaTransfer $merchantProfileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileCollectionTransfer
     */
    public function get(MerchantProfileCriteriaTransfer $merchantProfileCriteriaTransfer): MerchantProfileCollectionTransfer;

    /**
     * Specification:
     * - Saves merchant profile after the merchant is updated.
     * - Does not save merchant profile if MerchantTransfer.merchantProfile is not set.
     * - Creates a new merchant profile if MerchantTransfer.merchantProfile.idMerchantProfile is not set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function postUpdateMerchant(MerchantTransfer $merchantTransfer): MerchantResponseTransfer;

    /**
     * Specification:
     * - Hydrates the `Order.merchants` property.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateOrderMerchants(OrderTransfer $orderTransfer): OrderTransfer;

    /**
     * Specification:
     * - Expand CalculableObjectTransfer.Items with Address from MerchantProfile.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function expandQuoteItemsWithMerchantProfileAddress(CalculableObjectTransfer $quoteTransfer): CalculableObjectTransfer;

    /**
     * Specification:
     * - Expand OrderTransfer.Items with the first Address found in MerchantProfile.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderItemsWithMerchantProfileAddress(OrderTransfer $orderTransfer): OrderTransfer;

    /**
     * Specification:
     * - Requires `MerchantCollectionTransfer.merchants.idMerchant` to be set.
     * - Retrieves merchant profile data from Persistence by provided `Merchant.idMerchant` from collection.
     * - Expands each `MerchantTransfer` from `MerchantCollectionTransfer` with related `MerchantProfileTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCollectionTransfer $merchantCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function expandMerchantCollectionWithMerchantProfile(
        MerchantCollectionTransfer $merchantCollectionTransfer
    ): MerchantCollectionTransfer;
}
