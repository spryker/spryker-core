<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUnitAddressesRestApi\Dependency\Client;

use Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;

class CompanyUnitAddressesRestApiToCompanyUnitAddressClientBridge implements CompanyUnitAddressesRestApiToCompanyUnitAddressClientInterface
{
    /**
     * @var \Spryker\Client\CompanyUnitAddress\CompanyUnitAddressClientInterface
     */
    protected $companyUnitAddressClient;

    /**
     * @param \Spryker\Client\CompanyUnitAddress\CompanyUnitAddressClientInterface $companyUnitAddressClient
     */
    public function __construct($companyUnitAddressClient)
    {
        $this->companyUnitAddressClient = $companyUnitAddressClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer
     */
    public function findCompanyUnitAddressByUuid(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressResponseTransfer
    {
        return $this->companyUnitAddressClient->findCompanyUnitAddressByUuid($companyUnitAddressTransfer);
    }
}
