<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;

interface CompanyUnitAddressHydratePluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $addressTransfer
     *
     * @return void
     */
    public function hydrate(CompanyUnitAddressTransfer $addressTransfer);
}
