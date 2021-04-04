<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Business;

use Generated\Shared\Transfer\MerchantProfileCollectionTransfer;
use Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantProfileTransfer;
use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;

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
     * @param \Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer $merchantProfileCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer|null
     */
    public function findOne(MerchantProfileCriteriaFilterTransfer $merchantProfileCriteriaFilterTransfer): ?MerchantProfileTransfer;

    /**
     * Specification:
     * - Finds merchant profiles by Criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer $merchantProfileCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileCollectionTransfer
     */
    public function find(MerchantProfileCriteriaFilterTransfer $merchantProfileCriteriaFilterTransfer): MerchantProfileCollectionTransfer;

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
}
