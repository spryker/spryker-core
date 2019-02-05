<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUserAuthRestApi\Processor\RestUserIdentifier;

use Generated\Shared\Transfer\RestUserIdentifierTransfer;
use Spryker\Glue\CompanyUserAuthRestApi\Dependency\Client\CompanyUserAuthRestApiToCompanyUserStorageClientInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class RestUserIdentifierExpander implements RestUserIdentifierExpanderInterface
{
    /**
     * @var \Spryker\Glue\CompanyUserAuthRestApi\Dependency\Client\CompanyUserAuthRestApiToCompanyUserStorageClientInterface
     */
    protected $companyUserStorageClient;

    /**
     * @param \Spryker\Glue\CompanyUserAuthRestApi\Dependency\Client\CompanyUserAuthRestApiToCompanyUserStorageClientInterface $companyUserStorageClient
     */
    public function __construct(CompanyUserAuthRestApiToCompanyUserStorageClientInterface $companyUserStorageClient)
    {
        $this->companyUserStorageClient = $companyUserStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\RestUserIdentifierTransfer $restUserIdentifierTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestUserIdentifierTransfer
     */
    public function expand(RestUserIdentifierTransfer $restUserIdentifierTransfer, RestRequestInterface $restRequest): RestUserIdentifierTransfer
    {
        $uuidCompanyUser = $restUserIdentifierTransfer->getIdCompanyUser();
        if ($uuidCompanyUser === null) {
            return $restUserIdentifierTransfer;
        }

        $companyUserStorageTransfer = $this->companyUserStorageClient
            ->findCompanyUserByMapping('uuid', $uuidCompanyUser);

        if ($companyUserStorageTransfer !== null) {
            $restUserIdentifierTransfer->fromArray($companyUserStorageTransfer->toArray(true), true);
            $restUserIdentifierTransfer->setUuidCompanyUser($uuidCompanyUser);
        }

        return $restUserIdentifierTransfer;
    }
}
