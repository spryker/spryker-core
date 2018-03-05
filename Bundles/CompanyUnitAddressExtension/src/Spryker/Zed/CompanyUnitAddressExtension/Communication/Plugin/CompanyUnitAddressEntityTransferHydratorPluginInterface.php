<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressExtension\Communication\Plugin;

use Generated\Shared\Transfer\SpyCompanyUnitAddressEntityTransfer;

interface CompanyUnitAddressEntityTransferHydratorPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SpyCompanyUnitAddressEntityTransfer $addressEntityTransfer
     *
     * @return void
     */
    public function hydrate(SpyCompanyUnitAddressEntityTransfer $addressEntityTransfer);
}
