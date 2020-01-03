<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUserAuthRestApi\Processor\RestUser;

use Generated\Shared\Transfer\RestUserTransfer;
use Spryker\Glue\CompanyUserAuthRestApi\Dependency\Client\CompanyUserAuthRestApiToCompanyUserStorageClientInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class RestUserMapper implements RestUserMapperInterface
{
    protected const MAPPING_TYPE_UUID = 'uuid';

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
     * @param \Generated\Shared\Transfer\RestUserTransfer $restUserTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestUserTransfer
     */
    public function map(RestUserTransfer $restUserTransfer, RestRequestInterface $restRequest): RestUserTransfer
    {
        $uuidCompanyUser = (string)$restUserTransfer->getIdCompanyUser();
        if (!$uuidCompanyUser) {
            return $restUserTransfer;
        }

        $companyUserStorageTransfer = $this->companyUserStorageClient
            ->findCompanyUserByMapping(static::MAPPING_TYPE_UUID, $uuidCompanyUser);

        if (!$companyUserStorageTransfer) {
            $restUserTransfer->setIdCompanyUser(null);

            return $restUserTransfer;
        }

        $restUserTransfer->fromArray($companyUserStorageTransfer->toArray(), true);
        $restUserTransfer->setUuidCompanyUser($uuidCompanyUser);

        return $restUserTransfer;
    }
}
