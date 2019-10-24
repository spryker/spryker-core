<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Business;

use Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantProfileTransfer;
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
     * - Expands MerchantTransfer with merchant profile.
     * - Returns expanded MerchantTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function expandMerchantWithMerchantProfile(MerchantTransfer $merchantTransfer): MerchantTransfer;
}
