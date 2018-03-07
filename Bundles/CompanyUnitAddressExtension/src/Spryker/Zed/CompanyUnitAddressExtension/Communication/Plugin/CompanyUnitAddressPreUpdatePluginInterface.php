<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressExtension\Communication\Plugin;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
//TODO: move to Dependency/Plugin
interface CompanyUnitAddressPreUpdatePluginInterface
{
    //TODO: add specification instead of inheritdoc
    //TODO: rename plugin to postSave
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return void
     */
    public function preUpdate(CompanyUnitAddressTransfer $companyUnitAddressTransfer);
}
