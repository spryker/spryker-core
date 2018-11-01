<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabel\Persistence;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;

interface CompanyUnitAddressLabelEntityManagerInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return void
     */
    public function saveLabelToAddressRelations(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): void;

    /**
     * @api
     *
     * @param array $labelToAddressRelationIds
     *
     * @return void
     */
    public function deleteRedundantLabelToAddressRelations(
        array $labelToAddressRelationIds
    ): void;
}
