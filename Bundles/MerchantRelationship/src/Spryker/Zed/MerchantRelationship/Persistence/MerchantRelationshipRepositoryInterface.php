<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Persistence;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;

interface MerchantRelationshipRepositoryInterface
{
    /**
     * @param int $idMerchantRelationship
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer|null
     */
    public function getMerchantRelationshipById(int $idMerchantRelationship): ?MerchantRelationshipTransfer;

    /**
     * @param string $merchantRelationshipKey
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer|null
     */
    public function getMerchantRelationshipByKey(string $merchantRelationshipKey): ?MerchantRelationshipTransfer;

    /**
     * @param int $idMerchantRelationship
     *
     * @return int[]
     */
    public function getIdAssignedBusinessUnitsByMerchantRelationshipId(int $idMerchantRelationship): array;

    /**
     * @param int $idCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer[]
     */
    public function getAssignedMerchantRelationshipsByIdCompanyBusinessUnit(int $idCompanyBusinessUnit): array;

    /**
     * @param string $candidate
     *
     * @return bool
     */
    public function hasKey(string $candidate): bool;

    /**
     * @return int
     */
    public function getMaxMerchantRelationshipId(): int;

    /**
     * Specification:
     * - Returns all merchant relations.
     * - Hydrate owner company business unit and merchant
     *
     * @api
     *
     * @module CompanyBusinessUnit
     * @module Merchant
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer[]
     */
    public function getMerchantRelationshipCollection(): array;
}
