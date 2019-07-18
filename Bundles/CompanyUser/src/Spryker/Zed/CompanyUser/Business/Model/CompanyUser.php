<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\CompanyResponseTransfer;
use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaTransfer;
use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ResponseMessageTransfer;
use Spryker\Zed\CompanyUser\Dependency\Facade\CompanyUserToCustomerFacadeInterface;
use Spryker\Zed\CompanyUser\Persistence\CompanyUserEntityManagerInterface;
use Spryker\Zed\CompanyUser\Persistence\CompanyUserRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CompanyUser implements CompanyUserInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\CompanyUser\Persistence\CompanyUserRepositoryInterface
     */
    protected $companyUserRepository;

    /**
     * @var \Spryker\Zed\CompanyUser\Persistence\CompanyUserEntityManagerInterface
     */
    protected $companyUserEntityManager;

    /**
     * @var \Spryker\Zed\CompanyUser\Dependency\Facade\CompanyUserToCustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @var \Spryker\Zed\CompanyUser\Business\Model\CompanyUserPluginExecutorInterface
     */
    protected $companyUserPluginExecutor;

    /**
     * @var \Spryker\Zed\CompanyUserExtension\Dependency\Plugin\CompanyUserSavePreCheckPluginInterface[]
     */
    protected $companyUserSavePreCheckPlugins;

    /**
     * @param \Spryker\Zed\CompanyUser\Persistence\CompanyUserRepositoryInterface $companyUserRepository
     * @param \Spryker\Zed\CompanyUser\Persistence\CompanyUserEntityManagerInterface $companyUserEntityManager
     * @param \Spryker\Zed\CompanyUser\Dependency\Facade\CompanyUserToCustomerFacadeInterface $customerFacade
     * @param \Spryker\Zed\CompanyUser\Business\Model\CompanyUserPluginExecutorInterface $companyUserPluginExecutor
     * @param \Spryker\Zed\CompanyUserExtension\Dependency\Plugin\CompanyUserSavePreCheckPluginInterface[] $companyUserSavePreCheckPlugins
     */
    public function __construct(
        CompanyUserRepositoryInterface $companyUserRepository,
        CompanyUserEntityManagerInterface $companyUserEntityManager,
        CompanyUserToCustomerFacadeInterface $customerFacade,
        CompanyUserPluginExecutorInterface $companyUserPluginExecutor,
        array $companyUserSavePreCheckPlugins
    ) {
        $this->companyUserRepository = $companyUserRepository;
        $this->companyUserEntityManager = $companyUserEntityManager;
        $this->customerFacade = $customerFacade;
        $this->companyUserPluginExecutor = $companyUserPluginExecutor;
        $this->companyUserSavePreCheckPlugins = $companyUserSavePreCheckPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function create(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        $companyUserResponseTransfer = $this->executeSavePreCheckPlugins($companyUserTransfer);

        if (!$companyUserResponseTransfer->getIsSuccessful()) {
            return $companyUserResponseTransfer;
        }

        return $this->getTransactionHandler()->handleTransaction(function () use ($companyUserResponseTransfer) {
            return $this->executeCreateTransaction($companyUserResponseTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function save(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        $companyUserResponseTransfer = $this->executeSavePreCheckPlugins($companyUserTransfer);

        if (!$companyUserResponseTransfer->getIsSuccessful()) {
            return $companyUserResponseTransfer;
        }

        return $this->getTransactionHandler()->handleTransaction(function () use ($companyUserResponseTransfer) {
            $companyUserResponseTransfer = $this->executeSaveTransaction($companyUserResponseTransfer);

            return $companyUserResponseTransfer;
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function delete(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($companyUserTransfer) {
            $this->companyUserPluginExecutor->executePreDeletePlugins($companyUserTransfer);

            $companyUserTransfer = $this->companyUserRepository->getCompanyUserById(
                $companyUserTransfer->getIdCompanyUser()
            );

            $this->companyUserEntityManager->deleteCompanyUserById($companyUserTransfer->getIdCompanyUser());
            $this->customerFacade->anonymizeCustomer($companyUserTransfer->getCustomer());

            return (new CompanyUserResponseTransfer())->setIsSuccessful(true);
        });
    }

    /**
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function findCompanyUserByCustomerId(int $idCustomer): ?CompanyUserTransfer
    {
        $companyUserTransfer = $this->companyUserRepository->findCompanyUserByCustomerId($idCustomer);

        if ($companyUserTransfer !== null) {
            return $this->companyUserPluginExecutor->executeHydrationPlugins($companyUserTransfer);
        }

        return null;
    }

    /**
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function getCompanyUserById(int $idCompanyUser): CompanyUserTransfer
    {
        $companyUserTransfer = $this->companyUserRepository->getCompanyUserById($idCompanyUser);

        return $this->companyUserPluginExecutor->executeHydrationPlugins($companyUserTransfer);
    }

    /**
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function findActiveCompanyUserByCustomerId(int $idCustomer): ?CompanyUserTransfer
    {
        $companyUserTransfer = $this->companyUserRepository->findActiveCompanyUserByCustomerId($idCustomer);

        if ($companyUserTransfer !== null) {
            return $this->companyUserPluginExecutor->executeHydrationPlugins($companyUserTransfer);
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getActiveCompanyUsersByCustomerReference(CustomerTransfer $customerTransfer): CompanyUserCollectionTransfer
    {
        $customerTransfer->requireCustomerReference();

        $companyUserCollectionTransfer = $this->companyUserRepository->getActiveCompanyUsersByCustomerReference($customerTransfer->getCustomerReference());

        foreach ($companyUserCollectionTransfer->getCompanyUsers() as &$companyUserTransfer) {
            $this->companyUserPluginExecutor->executeHydrationPlugins($companyUserTransfer);
        }

        return $companyUserCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getCompanyUserCollection(
        CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer
    ): CompanyUserCollectionTransfer {
        $collectionTransfer = $this->companyUserRepository->getCompanyUserCollection($companyUserCriteriaFilterTransfer);

        foreach ($collectionTransfer->getCompanyUsers() as &$companyUserTransfer) {
            $this->companyUserPluginExecutor->executeHydrationPlugins($companyUserTransfer);
        }

        return $collectionTransfer;
    }

    /**
     * @param int[] $companyUserIds
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer[]
     */
    public function findActiveCompanyUsers(array $companyUserIds): array
    {
        return $this->companyUserRepository->findActiveCompanyUsersByIds($companyUserIds);
    }

    /**
     * @param int[] $companyIds
     *
     * @return int[]
     */
    public function findActiveCompanyUserIdsByCompanyIds(array $companyIds): array
    {
        return $this->companyUserRepository->findActiveCompanyUserIdsByCompanyIds($companyIds);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserResponseTransfer $companyUserResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    protected function executeCreateTransaction(CompanyUserResponseTransfer $companyUserResponseTransfer): CompanyUserResponseTransfer
    {
        $companyUserResponseTransfer = $this->registerCustomer($companyUserResponseTransfer);

        if (!$companyUserResponseTransfer->getIsSuccessful()) {
            return $companyUserResponseTransfer;
        }

        $companyUserResponseTransfer = $this->companyUserPluginExecutor->executePreSavePlugins($companyUserResponseTransfer);
        $companyUserTransfer = $companyUserResponseTransfer->getCompanyUser();
        $companyUserTransfer = $this->companyUserEntityManager->saveCompanyUser($companyUserTransfer);
        $companyUserResponseTransfer->setCompanyUser($companyUserTransfer);
        $companyUserResponseTransfer = $this->companyUserPluginExecutor->executePostSavePlugins($companyUserResponseTransfer);
        $companyUserResponseTransfer = $this->companyUserPluginExecutor->executePostCreatePlugins($companyUserResponseTransfer);

        return $companyUserResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserResponseTransfer $companyUserResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    protected function executeSaveTransaction(CompanyUserResponseTransfer $companyUserResponseTransfer): CompanyUserResponseTransfer
    {
        $companyUserResponseTransfer->requireCompanyUser();
        $companyUserResponseTransfer->getCompanyUser()->requireCustomer();

        $companyUserResponseTransfer = $this->updateCustomer($companyUserResponseTransfer);

        if (!$companyUserResponseTransfer->getIsSuccessful()) {
            return $companyUserResponseTransfer;
        }

        $companyUserResponseTransfer = $this->companyUserPluginExecutor->executePreSavePlugins($companyUserResponseTransfer);
        $companyUserTransfer = $companyUserResponseTransfer->getCompanyUser();
        $companyUserTransfer = $this->companyUserEntityManager->saveCompanyUser($companyUserTransfer);
        $companyUserTransfer->setCustomer($companyUserResponseTransfer->getCompanyUser()->getCustomer());
        $companyUserResponseTransfer->setCompanyUser($companyUserTransfer);
        $companyUserResponseTransfer = $this->companyUserPluginExecutor->executePostSavePlugins($companyUserResponseTransfer);

        return $companyUserResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserResponseTransfer $companyUserResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    protected function updateCustomer(CompanyUserResponseTransfer $companyUserResponseTransfer): CompanyUserResponseTransfer
    {
        $companyUserTransfer = $companyUserResponseTransfer->getCompanyUser();
        $customerResponseTransfer = $this->customerFacade->updateCustomer($companyUserTransfer->getCustomer());

        if ($customerResponseTransfer->getIsSuccess()) {
            $companyUserTransfer->setCustomer($customerResponseTransfer->getCustomerTransfer());

            return $companyUserResponseTransfer;
        }

        $companyUserResponseTransfer->setIsSuccessful(false);
        $companyUserResponseTransfer = $this->addErrorsToResponse(
            $companyUserResponseTransfer,
            $customerResponseTransfer->getErrors()
        );

        return $companyUserResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserResponseTransfer $companyUserResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    protected function registerCustomer(CompanyUserResponseTransfer $companyUserResponseTransfer): CompanyUserResponseTransfer
    {
        $companyUserResponseTransfer->requireCompanyUser();
        $companyUserResponseTransfer->getCompanyUser()->requireCustomer();

        $companyUserTransfer = $companyUserResponseTransfer->getCompanyUser();
        $customerTransfer = $companyUserTransfer->getCustomer();

        if ($customerTransfer->getIdCustomer()) {
            $companyUserTransfer->setFkCustomer($customerTransfer->getIdCustomer());

            return $companyUserResponseTransfer;
        }

        $customerResponseTransfer = $this->customerFacade->registerCustomer($customerTransfer);

        if ($customerResponseTransfer->getIsSuccess()) {
            $companyUserTransfer->setCustomer($customerResponseTransfer->getCustomerTransfer());
            $companyUserTransfer->setFkCustomer(
                $customerResponseTransfer->getCustomerTransfer()
                    ->getIdCustomer()
            );

            return $companyUserResponseTransfer;
        }

        $companyUserResponseTransfer->setIsSuccessful(false);

        $companyUserResponseTransfer = $this->addErrorsToResponse(
            $companyUserResponseTransfer,
            $customerResponseTransfer->getErrors()
        );

        return $companyUserResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyResponseTransfer $companyResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyResponseTransfer
     */
    public function createInitialCompanyUser(CompanyResponseTransfer $companyResponseTransfer): CompanyResponseTransfer
    {
        $companyResponseTransfer->getCompanyTransfer()->requireIdCompany()->requireInitialUserTransfer();

        $companyTransfer = $companyResponseTransfer->getCompanyTransfer();
        $companyUserTransfer = $companyTransfer->getInitialUserTransfer()
            ->setFkCompany($companyTransfer->getIdCompany());

        $companyUserResponseTransfer = $this->create($companyUserTransfer);

        $companyResponseTransfer->getCompanyTransfer()->setInitialUserTransfer($companyUserResponseTransfer->getCompanyUser());
        $companyResponseTransfer->setIsSuccessful($companyUserResponseTransfer->getIsSuccessful());
        $this->addMessagesToCompanyResponse($companyUserResponseTransfer->getMessages(), $companyResponseTransfer);

        return $companyResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function findActiveCompanyUserByUuid(CompanyUserTransfer $companyUserTransfer): ?CompanyUserTransfer
    {
        $companyUserTransfer->requireUuid();
        $companyUserTransfer = $this->companyUserRepository->findActiveCompanyUserByUuid($companyUserTransfer->getUuid());
        if ($companyUserTransfer !== null) {
            return $this->companyUserPluginExecutor->executeHydrationPlugins($companyUserTransfer);
        }

        return null;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ResponseMessageTransfer[] $messages
     * @param \Generated\Shared\Transfer\CompanyResponseTransfer $companyResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyResponseTransfer
     */
    protected function addMessagesToCompanyResponse(
        ArrayObject $messages,
        CompanyResponseTransfer $companyResponseTransfer
    ): CompanyResponseTransfer {
        foreach ($messages as $messageTransfer) {
            $companyResponseTransfer->addMessage($messageTransfer);
        }

        return $companyResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserResponseTransfer $companyUserResponseTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\CustomerErrorTransfer[] $errors
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    protected function addErrorsToResponse(CompanyUserResponseTransfer $companyUserResponseTransfer, ArrayObject $errors): CompanyUserResponseTransfer
    {
        foreach ($errors as $error) {
            $companyUserResponseTransfer->addMessage(
                (new ResponseMessageTransfer())->setText($error->getMessage())
            );
        }

        return $companyUserResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function deleteCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($companyUserTransfer) {
            $companyUserTransfer->requireIdCompanyUser();

            return $this->executeDeleteCompanyUserTransaction($companyUserTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    protected function executeDeleteCompanyUserTransaction(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        $this->companyUserPluginExecutor->executePreDeletePlugins($companyUserTransfer);

        $this->companyUserEntityManager->deleteCompanyUserById($companyUserTransfer->getIdCompanyUser());

        return (new CompanyUserResponseTransfer())->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    protected function executeSavePreCheckPlugins(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        foreach ($this->companyUserSavePreCheckPlugins as $companyUserSavePreCheckPlugin) {
            $companyUserResponseTransfer = $companyUserSavePreCheckPlugin->check($companyUserTransfer);
            if (!$companyUserResponseTransfer->getIsSuccessful()) {
                return $companyUserResponseTransfer;
            }
        }

        return (new CompanyUserResponseTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setIsSuccessful(true);
    }

    /**
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function findCompanyUserById(int $idCompanyUser): ?CompanyUserTransfer
    {
        $companyUserTransfer = $this->companyUserRepository->findCompanyUserById($idCompanyUser);

        if ($companyUserTransfer !== null) {
            return $this->companyUserPluginExecutor->executeHydrationPlugins($companyUserTransfer);
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaTransfer $companyUserCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getCompanyUserCollectionByCriteria(CompanyUserCriteriaTransfer $companyUserCriteriaTransfer): CompanyUserCollectionTransfer
    {
        $companyUserCollectionTransfer = $this->companyUserRepository
            ->getCompanyUserCollectionByCriteria($companyUserCriteriaTransfer);

        foreach ($companyUserCollectionTransfer->getCompanyUsers() as &$companyUserTransfer) {
            $this->companyUserPluginExecutor->executeHydrationPlugins($companyUserTransfer);
        }

        return $companyUserCollectionTransfer;
    }
}
