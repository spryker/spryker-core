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
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\ResponseMessageTransfer;
use Spryker\Zed\CompanyRole\CompanyRoleConfig;
use Spryker\Zed\CompanyRole\Dependency\Facade\CompanyRoleToPermissionFacadeInterface;
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
     * @var \Spryker\Zed\CompanyRole\Dependency\Facade\CompanyRoleToPermissionFacadeInterface
     */
    protected $permissionFacade;

    /**
     * @param \Spryker\Zed\CompanyRole\Persistence\CompanyRoleRepositoryInterface $repository
     * @param \Spryker\Zed\CompanyRole\Persistence\CompanyRoleEntityManagerInterface $entityManager
     * @param \Spryker\Zed\CompanyRole\Business\Model\CompanyRolePermissionWriterInterface $permissionWriter
     * @param \Spryker\Zed\CompanyRole\CompanyRoleConfig $companyRoleConfig
     * @param \Spryker\Zed\CompanyRole\Dependency\Facade\CompanyRoleToPermissionFacadeInterface $permissionFacade
     */
    public function __construct(
        CompanyRoleRepositoryInterface $repository,
        CompanyRoleEntityManagerInterface $entityManager,
        CompanyRolePermissionWriterInterface $permissionWriter,
        CompanyRoleConfig $companyRoleConfig,
        CompanyRoleToPermissionFacadeInterface $permissionFacade
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->permissionWriter = $permissionWriter;
        $this->companyRoleConfig = $companyRoleConfig;
        $this->permissionFacade = $permissionFacade;
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
        $companyRoles = $this->companyRoleConfig->getPredefinedCompanyRoles();

        if (!empty($companyRoles)) {
            return $this->createCompanyRoles($companyResponseTransfer, $companyRoles);
        }

        return $companyResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyResponseTransfer $companyResponseTransfer
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer[] $companyRoles
     *
     * @return \Generated\Shared\Transfer\CompanyResponseTransfer
     */
    protected function createCompanyRoles(
        CompanyResponseTransfer $companyResponseTransfer,
        array $companyRoles
    ): CompanyResponseTransfer {
        $companyResponseTransfer->requireCompanyTransfer();
        $companyTransfer = $companyResponseTransfer->getCompanyTransfer();

        $availablePermissions = $this->permissionFacade->findMergedRegisteredNonInfrastructuralPermissions();

        foreach ($companyRoles as $companyRoleTransfer) {
            $companyRoleResponseTransfer = $this->createCompanyRoleWithAssignedPermissions(
                $companyRoleTransfer,
                $companyTransfer,
                $availablePermissions
            );

            $companyResponseTransfer = $this->addCompanyRoleMessagesToCompanyResponseTransfer(
                $companyRoleResponseTransfer,
                $companyResponseTransfer
            );
        }

        return $companyResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleResponseTransfer $companyRoleResponseTransfer
     * @param \Generated\Shared\Transfer\CompanyResponseTransfer $companyResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyResponseTransfer
     */
    protected function addCompanyRoleMessagesToCompanyResponseTransfer(
        CompanyRoleResponseTransfer $companyRoleResponseTransfer,
        CompanyResponseTransfer $companyResponseTransfer
    ): CompanyResponseTransfer {
        foreach ($companyRoleResponseTransfer->getMessages() as $messageTransfer) {
            $companyResponseTransfer->addMessage($messageTransfer);
        }

        return $companyResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     * @param \Generated\Shared\Transfer\PermissionCollectionTransfer $availablePermissions
     *
     * @return \Generated\Shared\Transfer\CompanyRoleResponseTransfer
     */
    protected function createCompanyRoleWithAssignedPermissions(
        CompanyRoleTransfer $companyRoleTransfer,
        CompanyTransfer $companyTransfer,
        PermissionCollectionTransfer $availablePermissions
    ): CompanyRoleResponseTransfer {
        $companyRoleTransfer->setFkCompany($companyTransfer->getIdCompany());

        $preparedPermissionCollection = $this->findAssignedCompanyRolePermissions(
            $companyRoleTransfer->getPermissionCollection(),
            $availablePermissions
        );

        $companyRoleTransfer->setPermissionCollection($preparedPermissionCollection);

        return $this->create($companyRoleTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PermissionCollectionTransfer $companyRolePermissions
     * @param \Generated\Shared\Transfer\PermissionCollectionTransfer $availablePermissions
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    protected function findAssignedCompanyRolePermissions(
        PermissionCollectionTransfer $companyRolePermissions,
        PermissionCollectionTransfer $availablePermissions
    ): PermissionCollectionTransfer {
        $availableCompanyRolePermissions = new PermissionCollectionTransfer();

        foreach ($companyRolePermissions->getPermissions() as $assignedCompanyRolePermissionTransfer) {
            foreach ($availablePermissions->getPermissions() as $availablePermissionTransfer) {
                if ($assignedCompanyRolePermissionTransfer->getKey() === $availablePermissionTransfer->getKey()) {
                    $availableCompanyRolePermissions->addPermission($availablePermissionTransfer);
                }
            }
        }

        return $availableCompanyRolePermissions;
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
                ->setIsSuccessful(false)
                ->addMessage(
                    (new ResponseMessageTransfer())
                        ->setText(static::ERROR_MESSAGE_HAS_RELATED_USERS)
                );

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
