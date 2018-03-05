<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressExtension\Communication\Plugin;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;

interface CompanyUnitAddressTransferHydratorPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $addressTransfer
     *
     * @return void
     */
    public function hydrate(CompanyUnitAddressTransfer $addressTransfer);
}
