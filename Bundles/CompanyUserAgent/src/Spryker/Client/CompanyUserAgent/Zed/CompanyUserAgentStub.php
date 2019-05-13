<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyUserAgent\Zed;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaTransfer;
use Spryker\Client\CompanyUserAgent\Dependency\Client\CompanyUserAgentToZedRequestClientInterface;

class CompanyUserAgentStub implements CompanyUserAgentStubInterface
{
    /**
     * @var \Spryker\Client\CompanyUserAgent\Dependency\Client\CompanyUserAgentToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\CompanyUserAgent\Dependency\Client\CompanyUserAgentToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(CompanyUserAgentToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaTransfer $companyUserCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getCompanyUserCollectionByCriteria(CompanyUserCriteriaTransfer $companyUserCriteriaTransfer): CompanyUserCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\CompanyUserCollectionTransfer $companyUserCollectionTransfer */
        $companyUserCollectionTransfer = $this->zedRequestClient->call(
            '/company-user-agent/gateway/get-company-user-collection-by-criteria',
            $companyUserCriteriaTransfer
        );

        return $companyUserCollectionTransfer;
    }
}
