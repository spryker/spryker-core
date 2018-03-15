<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Business\Model;

use Generated\Shared\Transfer\CompanyResponseTransfer;
use Generated\Shared\Transfer\CompanyRoleCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyRoleResponseTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\ResponseMessageTransfer;
use Spryker\Zed\CompanyRole\CompanyRoleConfig;
use Spryker\Zed\CompanyRole\Persistence\CompanyRoleEntityManagerInterface;
use Spryker\Zed\CompanyRole\Persistence\CompanyRoleRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CompanyRole implements CompanyRoleInterface
{
    use TransactionTrait;

    protected const ERROR_MESSAGE_HAS_RELATED_USERS = 'company.company_role.delete.error.has_users';

    /**
     * @var \Spryker\Zed\CompanyRole\Persistence\CompanyRoleRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\CompanyRole\Persistence\CompanyRoleEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\CompanyRole\Business\Model\CompanyRolePermissionWriterInterface
     */
    protected $permissionWriter;

    /**
     * @var \Spryker\Zed\CompanyRole\CompanyRoleConfig
     */
    protected $companyRoleConfig;

    /**
     * @param \Spryker\Zed\CompanyRole\Persistence\CompanyRoleRepositoryInterface $repository
     * @param \Spryker\Zed\CompanyRole\Persistence\CompanyRoleEntityManagerInterface $entityManager
     * @param \Spryker\Zed\CompanyRole\Business\Model\CompanyRolePermissionWriterInterface $permissionWriter
     * @param \Spryker\Zed\CompanyRole\CompanyRoleConfig $companyRoleConfig
     */
    public function __construct(
        CompanyRoleRepositoryInterface $repository,
        CompanyRoleEntityManagerInterface $entityManager,
        CompanyRolePermissionWriterInterface $permissionWriter,
        CompanyRoleConfig $companyRoleConfig
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->permissionWriter = $permissionWriter;
        $this->companyRoleConfig = $companyRoleConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleResponseTransfer
     */
    public function create(CompanyRoleTransfer $companyRoleTransfer): CompanyRoleResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($companyRoleTransfer) {
            return $this->executeCompanyRoleSaveTransaction($companyRoleTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyResponseTransfer $companyResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyResponseTransfer
     */
    public function createByCompany(CompanyResponseTransfer $companyResponseTransfer): CompanyResponseTransfer
    {
        $companyTransfer = $companyResponseTransfer->getCompanyTransfer();

        $companyRoleTransfer = (new CompanyRoleTransfer())
            ->setFkCompany($companyTransfer->getIdCompany())
            ->setName($this->companyRoleConfig->getDefaultAdminRoleName())
            ->setIsDefault(true);

        $companyRoleResponseTransfer = $this->create($companyRoleTransfer);

        if ($companyRoleResponseTransfer->getIsSuccessful()) {
            return $companyResponseTransfer;
        }

        foreach ($companyRoleResponseTransfer->getMessages() as $messageTransfer) {
            $companyResponseTransfer->addMessage($messageTransfer);
        }

        return $companyResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return void
     */
    public function update(CompanyRoleTransfer $companyRoleTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($companyRoleTransfer) {
            $this->executeCompanyRoleSaveTransaction($companyRoleTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleResponseTransfer
     */
    public function delete(CompanyRoleTransfer $companyRoleTransfer): CompanyRoleResponseTransfer
    {
        $companyRoleResponseTransfer = (new CompanyRoleResponseTransfer())
            ->setCompanyRoleTransfer($companyRoleTransfer)
            ->setIsSuccessful(true);

        return $this->getTransactionHandler()->handleTransaction(function () use ($companyRoleResponseTransfer) {
            return $this->executeDeleteTransaction($companyRoleResponseTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return void
     */
    public function saveCompanyUser(CompanyUserTransfer $companyUserTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($companyUserTransfer) {
            $this->entityManager->saveCompanyUser($companyUserTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function hydrateCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserTransfer
    {
        $companyUserTransfer->requireIdCompanyUser();

        $criteriaFilterTransfer = new CompanyRoleCriteriaFilterTransfer();
        $criteriaFilterTransfer->setIdCompanyUser($companyUserTransfer->getIdCompanyUser());
        $companyRoleCollection = $this->repository->getCompanyRoleCollection($criteriaFilterTransfer);

        return $companyUserTransfer->setCompanyRoleCollection($companyRoleCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleResponseTransfer $companyRoleResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleResponseTransfer
     */
    protected function executeDeleteTransaction(CompanyRoleResponseTransfer $companyRoleResponseTransfer): CompanyRoleResponseTransfer
    {
        $companyRoleResponseTransfer
            ->getCompanyRoleTransfer()
            ->requireIdCompanyRole();

        $companyRoleResponseTransfer = $this->checkOnRelatedUsers($companyRoleResponseTransfer);

        if (!$companyRoleResponseTransfer->getIsSuccessful()) {
            return $companyRoleResponseTransfer;
        }

        $this->entityManager->deleteCompanyRoleById(
            $companyRoleResponseTransfer
                ->getCompanyRoleTransfer()
                ->getIdCompanyRole()
        );

        return $companyRoleResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleResponseTransfer $companyRoleResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleResponseTransfer
     */
    protected function checkOnRelatedUsers(CompanyRoleResponseTransfer $companyRoleResponseTransfer): CompanyRoleResponseTransfer
    {
        $hasUsers = $this->repository->hasUsers(
            $companyRoleResponseTransfer
                ->getCompanyRoleTransfer()
                ->getIdCompanyRole()
        );

        if ($hasUsers) {
            $companyRoleResponseTransfer
                ->addMessage(
                    (new ResponseMessageTransfer())
                        ->setText(static::ERROR_MESSAGE_HAS_RELATED_USERS)
                )
                ->setIsSuccessful(false);

            return $companyRoleResponseTransfer;
        }

        return $companyRoleResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleResponseTransfer
     */
    protected function executeCompanyRoleSaveTransaction(CompanyRoleTransfer $companyRoleTransfer): CompanyRoleResponseTransfer
    {
        $permissionCollection = $companyRoleTransfer->getPermissionCollection();
        $companyRoleTransfer = $this->entityManager->saveCompanyRole($companyRoleTransfer);
        $companyRoleTransfer->setPermissionCollection($permissionCollection);

        $this->permissionWriter->saveCompanyRolePermissions($companyRoleTransfer);

        return (new CompanyRoleResponseTransfer())
            ->setIsSuccessful(true)
            ->setCompanyRoleTransfer($companyRoleTransfer);
    }
}
