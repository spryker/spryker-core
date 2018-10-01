<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyUser\Zed;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\ResponseMessageTransfer;
use Spryker\Client\Kernel\PermissionAwareTrait;
use Spryker\Client\ZedRequest\ZedRequestClient;

class CompanyUserStub implements CompanyUserStubInterface
{
    use PermissionAwareTrait;

    protected const ERROR_MESSAGE_PERMISSION_FAILED = 'global.permission.failed';

    /**
     * @var \Spryker\Client\ZedRequest\ZedRequestClient
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\ZedRequest\ZedRequestClient $zedRequestClient
     */
    public function __construct(ZedRequestClient $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function createCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        if (!$this->can('AddCompanyUserPermissionPlugin')) {
            return $this->generatePermissionErrorMessage();
        }

        /** @var \Generated\Shared\Transfer\CompanyUserResponseTransfer $companyUserResponseTransfer */
        $companyUserResponseTransfer = $this->zedRequestClient->call('/company-user/gateway/create', $companyUserTransfer);

        return $companyUserResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function updateCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\CompanyUserResponseTransfer $companyUserResponseTransfer */
        $companyUserResponseTransfer = $this->zedRequestClient->call('/company-user/gateway/update', $companyUserTransfer);

        return $companyUserResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function deleteCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\CompanyUserResponseTransfer $companyUserResponseTransfer */
        $companyUserResponseTransfer = $this->zedRequestClient->call('/company-user/gateway/delete', $companyUserTransfer);

        return $companyUserResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getCompanyUserCollection(
        CompanyUserCriteriaFilterTransfer $criteriaFilterTransfer
    ): CompanyUserCollectionTransfer {
        /** @var \Generated\Shared\Transfer\CompanyUserCollectionTransfer $companyUserCollectionTransfer */
        $companyUserCollectionTransfer = $this->zedRequestClient->call(
            '/company-user/gateway/get-company-user-collection',
            $criteriaFilterTransfer
        );

        return $companyUserCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function getCompanyUserById(CompanyUserTransfer $companyUserTransfer): CompanyUserTransfer
    {
        /** @var \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer */
        $companyUserTransfer = $this->zedRequestClient->call(
            '/company-user/gateway/get-company-user-by-id',
            $companyUserTransfer
        );

        return $companyUserTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    protected function generatePermissionErrorMessage(): CompanyUserResponseTransfer
    {
        $companyUserResponseTransfer = new CompanyUserResponseTransfer();
        $companyUserResponseTransfer->addMessage(
            (new ResponseMessageTransfer())
                ->setText(static::ERROR_MESSAGE_PERMISSION_FAILED)
        );
        $companyUserResponseTransfer->setIsSuccessful(false);

        return $companyUserResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function enableCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\CompanyUserResponseTransfer $companyUserResponseTransfer */
        $companyUserResponseTransfer = $this->zedRequestClient->call(
            '/company-user/gateway/enable-company-user',
            $companyUserTransfer
        );

        return $companyUserResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function disableCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\CompanyUserResponseTransfer $companyUserResponseTransfer */
        $companyUserResponseTransfer = $this->zedRequestClient->call(
            '/company-user/gateway/disable-company-user',
            $companyUserTransfer
        );

        return $companyUserResponseTransfer;
    }
}
