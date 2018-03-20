<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabel\Business\Model;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;

interface CompanyUnitAddressHydratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function hydrate(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressTransfer;
}
