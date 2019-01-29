<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Persistence;

use Generated\Shared\Transfer\MerchantAddressCollectionTransfer;
use Generated\Shared\Transfer\MerchantAddressTransfer;
use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantTransfer;

interface MerchantRepositoryInterface
{
    /**
     * Specification:
     * - Returns a MerchantTransfer by merchant id.
     * - Returns null in case a record is not found.
     *
     * @api
     *
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer|null
     */
    public function getMerchantById(int $idMerchant): ?MerchantTransfer;

    /**
     * Specification:
     * - Returns a MerchantTransfer by merchant email.
     * - Returns null in case a record is not found.
     *
     * @api
     *
     * @param string $merchantEmail
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer|null
     */
    public function getMerchantByEmail(string $merchantEmail): ?MerchantTransfer;

    /**
     * Specification:
     * - Retrieves collection of all merchants.
     * - List of merchants is ordered by merchant name.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function getMerchants(): MerchantCollectionTransfer;

    /**
     * Specification:
     * - Checks whether merchant key already exists.
     *
     * @api
     *
     * @param string $key
     *
     * @return bool
     */
    public function hasKey(string $key): bool;

    /**
     * Specification:
     * - Retrieves collection of merchant addresses.
     *
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantAddressCollectionTransfer
     */
    public function getMerchantAddresses(int $idMerchant): MerchantAddressCollectionTransfer;

    /**
     * Specification:
     * - Returns a MerchantAddressTransfer by merchant address id.
     * - Returns null in case a record is not found.
     *
     * @api
     *
     * @param int $idMerchantAddress
     *
     * @return \Generated\Shared\Transfer\MerchantAddressTransfer|null
     */
    public function getMerchantAddressById(int $idMerchantAddress): ?MerchantAddressTransfer;

    /**
     * Specification:
     * - Checks whether merchant address key already exists.
     *
     * @param string $key
     *
     * @return bool
     */
    public function hasAddressKey(string $key): bool;
}
