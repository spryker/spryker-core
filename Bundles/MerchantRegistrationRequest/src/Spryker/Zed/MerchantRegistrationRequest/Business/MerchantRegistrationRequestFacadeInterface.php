<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRegistrationRequest\Business;

use Generated\Shared\Transfer\MerchantRegistrationRequestTransfer;
use Generated\Shared\Transfer\MerchantRegistrationResponseTransfer;

interface MerchantRegistrationRequestFacadeInterface
{
    /**
     * Specification:
     * - Creates a new merchant registration request.
     * - Persists the data in database.
     *
     * @api
     */
    public function createMerchantRegistrationRequest(
        MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer
    ): MerchantRegistrationResponseTransfer;

    /**
     * Specification:
     * - Finds a merchant registration request by ID.
     * - Returns null if not found.
     *
     * @api
     */
    public function findMerchantRegistrationRequestById(int $idMerchantRegistrationRequest): ?MerchantRegistrationRequestTransfer;

    /**
     * Specification:
     * - Creates a new merchant.
     * - Creates a new user for the merchant.
     * - Updates the status of the merchant registration request to 'accepted'.
     *
     * @api
     */
    public function acceptMerchantRegistrationRequest(
        MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer
    ): MerchantRegistrationResponseTransfer;

    /**
     * Specification:
     * - Updates the status of the merchant registration request to 'rejected'.
     *
     * @api
     */
    public function rejectMerchantRegistrationRequest(
        MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer
    ): MerchantRegistrationResponseTransfer;

    /**
     * Specification:
     * - Expands the merchant registration request with a comment thread.
     *
     * @api
     */
    public function expandMerchantRegistrationRequestWithCommentThread(
        MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer
    ): MerchantRegistrationRequestTransfer;
}
