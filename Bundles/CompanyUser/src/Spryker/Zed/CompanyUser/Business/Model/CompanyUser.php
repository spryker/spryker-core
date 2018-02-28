<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Business\Model;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
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
     * @param \Spryker\Zed\CompanyUser\Persistence\CompanyUserRepositoryInterface $companyUserRepository
     * @param \Spryker\Zed\CompanyUser\Persistence\CompanyUserEntityManagerInterface $companyUserEntityManager
     * @param \Spryker\Zed\CompanyUser\Dependency\Facade\CompanyUserToCustomerFacadeInterface $customerFacade
     * @param \Spryker\Zed\CompanyUser\Business\Model\CompanyUserPluginExecutorInterface $companyUserPluginExecutor
     */
    public function __construct(
        CompanyUserRepositoryInterface $companyUserRepository,
        CompanyUserEntityManagerInterface $companyUserEntityManager,
        CompanyUserToCustomerFacadeInterface $customerFacade,
        CompanyUserPluginExecutorInterface $companyUserPluginExecutor
    ) {
        $this->companyUserRepository = $companyUserRepository;
        $this->companyUserEntityManager = $companyUserEntityManager;
        $this->customerFacade = $customerFacade;
        $this->companyUserPluginExecutor = $companyUserPluginExecutor;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function create(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($companyUserTransfer) {
            return $this->executeCompanyUserCreateTransaction($companyUserTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function save(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($companyUserTransfer) {
            return $this->executeSaveCompanyUserTransaction($companyUserTransfer);
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
            $companyUserTransfer->requireCustomer();
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
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getCompanyUserCollection(
        CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer
    ): CompanyUserCollectionTransfer {
        $collectionTransfer = $this->companyUserRepository->filterCompanyUsers($companyUserCriteriaFilterTransfer);

        foreach ($collectionTransfer->getCompanyUsers() as &$companyUserTransfer) {
            $this->companyUserPluginExecutor->executeHydrationPlugins($companyUserTransfer);
        }

        return $collectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    protected function executeCompanyUserCreateTransaction(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        $companyUserTransfer->requireCustomer();
        $customerResponseTransfer = $this->customerFacade->registerCustomer(
            $companyUserTransfer->getCustomer()
        );

        $companyUserResponseTransfer = new CompanyUserResponseTransfer();
        $companyUserResponseTransfer->setIsSuccessful(true);

        if (!$customerResponseTransfer->getIsSuccess()) {
            $companyUserResponseTransfer->setIsSuccessful(false);
            $companyUserResponseTransfer->setCompanyUser($companyUserTransfer);

            foreach ($customerResponseTransfer->getErrors() as $error) {
                $message = new ResponseMessageTransfer();
                $message->setText($error->getMessage());
                $companyUserResponseTransfer->addMessage($message);
            }

            return $companyUserResponseTransfer;
        }

        $companyUserTransfer->setFkCustomer($customerResponseTransfer->getCustomerTransfer()->getIdCustomer());
        $companyUserTransfer = $this->executeSaveCompanyUserTransaction($companyUserTransfer);
        $companyUserTransfer->setCustomer($customerResponseTransfer->getCustomerTransfer());

        $companyUserResponseTransfer->setCompanyUser($companyUserTransfer);

        return $companyUserResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected function executeSaveCompanyUserTransaction(
        CompanyUserTransfer $companyUserTransfer
    ): CompanyUserTransfer {
        $companyUserTransfer = $this->companyUserEntityManager->saveCompanyUser($companyUserTransfer);

        return $this->companyUserPluginExecutor->executePostSavePlugins($companyUserTransfer);
    }
}
