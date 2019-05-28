<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUserAuthRestApi\Dependency\Client;

use Generated\Shared\Transfer\CompanyUserStorageTransfer;

class CompanyUserAuthRestApiToCompanyUserStorageClientBridge implements CompanyUserAuthRestApiToCompanyUserStorageClientInterface
{
    /**
     * @var \Spryker\Client\CompanyUserStorage\CompanyUserStorageClientInterface
     */
    protected $companyUserStorageClient;

    /**
     * @param \Spryker\Client\CompanyUserStorage\CompanyUserStorageClientInterface $companyUserStorageClient
     */
    public function __construct($companyUserStorageClient)
    {
        $this->companyUserStorageClient = $companyUserStorageClient;
    }

    /**
     * @param string $mappingType
     * @param string $identifier
     *
     * @return \Generated\Shared\Transfer\CompanyUserStorageTransfer|null
     */
    public function findCompanyUserByMapping(string $mappingType, string $identifier): ?CompanyUserStorageTransfer
    {
        return $this->companyUserStorageClient->findCompanyUserByMapping($mappingType, $identifier);
    }
}
