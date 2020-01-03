<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUserStorage\Communication\Plugin\SharedCartsRestApi;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\SharedCartsRestApiExtension\Dependency\Plugin\CompanyUserProviderPluginInterface;

/**
 * @method \Spryker\Glue\CompanyUserStorage\CompanyUserStorageFactory getFactory()
 */
class CompanyUserStorageProviderPlugin extends AbstractPlugin implements CompanyUserProviderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Provides the company user information from key-value storage.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function provideCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserTransfer
    {
        return $this->getFactory()->createStorageCompanyUserProvider()
            ->provideCompanyUserFromStorage($companyUserTransfer);
    }
}
