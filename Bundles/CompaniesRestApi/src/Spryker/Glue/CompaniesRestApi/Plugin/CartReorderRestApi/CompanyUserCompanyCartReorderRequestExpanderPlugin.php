<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompaniesRestApi\Plugin\CartReorderRestApi;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\RestUserTransfer;
use Spryker\Glue\CartReorderRestApiExtension\Dependency\Plugin\CartReorderRequestExpanderPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

class CompanyUserCompanyCartReorderRequestExpanderPlugin extends AbstractPlugin implements CartReorderRequestExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Does nothing if `RestUserTransfer.idCompany` is not set.
     * - Maps `RestUserTransfer.idCompany` to `CompanyUserTransfer.fkCompany`.
     * - Maps `RestUserTransfer.idCompanyUser` to `CompanyUserTransfer.idCompanyUser` if `CompanyUserTransfer.idCompanyUser` is not set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     * @param \Generated\Shared\Transfer\RestUserTransfer $restUserTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderRequestTransfer
     */
    public function expand(
        CartReorderRequestTransfer $cartReorderRequestTransfer,
        RestUserTransfer $restUserTransfer
    ): CartReorderRequestTransfer {
        if ($restUserTransfer->getIdCompany()) {
            $companyUserTransfer = $cartReorderRequestTransfer->getCompanyUserTransfer() ?: new CompanyUserTransfer();
            $companyUserTransfer->setFkCompany($restUserTransfer->getIdCompany());
            if (!$companyUserTransfer->getIdCompanyUser() && $restUserTransfer->getIdCompanyUser()) {
                $companyUserTransfer->setIdCompanyUser($restUserTransfer->getIdCompanyUser());
            }
            $cartReorderRequestTransfer->setCompanyUserTransfer($companyUserTransfer);
        }

        return $cartReorderRequestTransfer;
    }
}
