<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyBusinessUnitSalesConnector;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\CompanyBusinessUnitSalesConnector\CompanyBusinessUnitSalesConnectorFactory getFactory()
 */
class CompanyBusinessUnitSalesConnectorClient extends AbstractClient implements CompanyBusinessUnitSalesConnectorClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer
     */
    public function getPermittedCompanyBusinessUnitCollection(
        CompanyUserTransfer $companyUserTransfer
    ): CompanyBusinessUnitCollectionTransfer {
        return $this->getFactory()
            ->createZedCompanyBusinessUnitSalesConnectorStub()
            ->getPermittedCompanyBusinessUnitCollection($companyUserTransfer);
    }
}
