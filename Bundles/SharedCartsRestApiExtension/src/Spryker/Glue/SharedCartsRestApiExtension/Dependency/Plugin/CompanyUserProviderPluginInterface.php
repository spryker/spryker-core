<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SharedCartsRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CompanyUserTransfer;

interface CompanyUserProviderPluginInterface
{
    /**
     * Specification:
     * - Provides company user.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function provideCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserTransfer;
}
