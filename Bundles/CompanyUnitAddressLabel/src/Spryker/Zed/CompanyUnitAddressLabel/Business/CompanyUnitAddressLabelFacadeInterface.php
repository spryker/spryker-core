<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabel\Business;

use Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;

interface CompanyUnitAddressLabelFacadeInterface
{
    /**
     * Specification:
     *  - Save label to address relation to their relations table.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer
     */
    public function saveLabelToAddressRelations(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressResponseTransfer;

    /**
     * Specification:
     *  - Fill a labelCollection property of company unit address.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function hydrateCompanyUnitAddressWithLabelCollection(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressTransfer;
}
