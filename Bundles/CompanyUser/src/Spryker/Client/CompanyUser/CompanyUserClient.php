<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyUser;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Client\Kernel\AbstractClient;
use Spryker\Client\Kernel\PermissionAwareTrait;

/**
 * @method \Spryker\Client\CompanyUser\CompanyUserFactory getFactory()
 */
class CompanyUserClient extends AbstractClient implements CompanyUserClientInterface
{
    use PermissionAwareTrait;

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function createCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        return $this->getFactory()
            ->createZedCompanyUserStub()
            ->createCompanyUser($companyUserTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function updateCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        return $this->getFactory()
            ->createZedCompanyUserStub()
            ->updateCompanyUser($companyUserTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function deleteCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        return $this->getFactory()
            ->createZedCompanyUserStub()
            ->deleteCompanyUser($companyUserTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getCompanyUserCollection(
        CompanyUserCriteriaFilterTransfer $criteriaFilterTransfer
    ): CompanyUserCollectionTransfer {
        return $this->getFactory()
            ->createZedCompanyUserStub()
            ->getCompanyUserCollection($criteriaFilterTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function getCompanyUserById(CompanyUserTransfer $companyUserTransfer): CompanyUserTransfer
    {
        return $this->getFactory()
            ->createZedCompanyUserStub()
            ->getCompanyUserById($companyUserTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @uses \SprykerShop\Shared\CompanyPage\Plugin\CompanyUserStatusChangePermissionPlugin
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function enableCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        if ($this->can('CompanyUserChangePermissionPlugin')) {
            return $this->getFactory()
                ->createZedCompanyUserStub()
                ->enableCompanyUser($companyUserTransfer);
        }

        return (new CompanyUserResponseTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setIsSuccessful(false);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @uses \SprykerShop\Shared\CompanyPage\Plugin\CompanyUserStatusChangePermissionPlugin
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function disableCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        if ($this->can('CompanyUserChangePermissionPlugin')) {
            return $this->getFactory()
                ->createZedCompanyUserStub()
                ->disableCompanyUser($companyUserTransfer);
        }

        return (new CompanyUserResponseTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setIsSuccessful(false);
    }
}
