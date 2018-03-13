<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabel\Persistence;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;

interface CompanyUnitAddressLabelRepositoryInterface
{
    /**
     * @return \Generated\Shared\Transfer\CompanyUnitAddressLabelCollectionTransfer
     */
    public function findCompanyUnitAddressLabels();

    /**
     * @param int $idCompanyUnitAddress
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressLabelCollectionTransfer
     */
    public function findCompanyUnitAddressLabelsByAddress(int $idCompanyUnitAddress);

    /**
     * @param int $idCompanyUnitAddress
     *
     * @return \ArrayObject
     */
    public function findCompanyUnitAddressLabelToCompanyUnitAddressRelations(int $idCompanyUnitAddress);

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return int[]
     */
    public function findCompanyUnitAddressLabelIdsByAddress(CompanyUnitAddressTransfer $companyUnitAddressTransfer);

    /**
     * @param int $idCompanyUnitAddress
     * @param int[] $labelIds
     *
     * @return int[]
     */
    public function findCompanyUnitAddressLabelToCompanyUnitAddressRelationIdsByAddressIdAndLabelIds(
        int $idCompanyUnitAddress,
        array $labelIds
    ): array;
}
