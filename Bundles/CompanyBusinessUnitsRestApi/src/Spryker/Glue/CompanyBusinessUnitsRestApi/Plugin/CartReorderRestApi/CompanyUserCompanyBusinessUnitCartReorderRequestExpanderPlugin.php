<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitsRestApi\Plugin\CartReorderRestApi;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\RestUserTransfer;
use Spryker\Glue\CartReorderRestApiExtension\Dependency\Plugin\CartReorderRequestExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

class CompanyUserCompanyBusinessUnitCartReorderRequestExpanderPlugin extends AbstractPlugin implements CartReorderRequestExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Does nothing if `RestUserTransfer.idCompanyBusinessUnit` is not set.
     * - Maps `RestUserTransfer.idCompanyBusinessUnit` to `CompanyUserTransfer.fkCompanyBusinessUnit`.
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
        if ($restUserTransfer->getIdCompanyBusinessUnit()) {
            $companyUserTransfer = $cartReorderRequestTransfer->getCompanyUserTransfer() ?: new CompanyUserTransfer();
            $companyUserTransfer->setFkCompanyBusinessUnit($restUserTransfer->getIdCompanyBusinessUnit());
            if (!$companyUserTransfer->getIdCompanyUser() && $restUserTransfer->getIdCompanyUser()) {
                $companyUserTransfer->setIdCompanyUser($restUserTransfer->getIdCompanyUser());
            }
            $cartReorderRequestTransfer->setCompanyUserTransfer($companyUserTransfer);
        }

        return $cartReorderRequestTransfer;
    }
}
