<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OauthCompanyUser\Processor\RestUser;

use Generated\Shared\Transfer\RestUserTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\OauthCompanyUser\Dependency\Client\OauthCompanyUserToCompanyUserStorageClientInterface;

class RestUserExpander implements RestUserExpanderInterface
{
    protected const MAPPING_TYPE_UUID = 'uuid';

    /**
     * @var \Spryker\Glue\OauthCompanyUser\Dependency\Client\OauthCompanyUserToCompanyUserStorageClientInterface
     */
    protected $companyUserStorageClient;

    /**
     * @param \Spryker\Glue\OauthCompanyUser\Dependency\Client\OauthCompanyUserToCompanyUserStorageClientInterface $companyUserStorageClient
     */
    public function __construct(OauthCompanyUserToCompanyUserStorageClientInterface $companyUserStorageClient)
    {
        $this->companyUserStorageClient = $companyUserStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\RestUserTransfer $restUserTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestUserTransfer
     */
    public function expand(RestUserTransfer $restUserTransfer, RestRequestInterface $restRequest): RestUserTransfer
    {
        $uuidCompanyUser = (string)$restUserTransfer->getIdCompanyUser();
        if (!$uuidCompanyUser) {
            return $restUserTransfer;
        }

        $companyUserStorageTransfer = $this->companyUserStorageClient
            ->findCompanyUserByMapping(static::MAPPING_TYPE_UUID, $uuidCompanyUser);

        if ($companyUserStorageTransfer !== null) {
            $restUserTransfer->fromArray($companyUserStorageTransfer->toArray(), true);
            $restUserTransfer->setUuidCompanyUser($uuidCompanyUser);
        }

        return $restUserTransfer;
    }
}
