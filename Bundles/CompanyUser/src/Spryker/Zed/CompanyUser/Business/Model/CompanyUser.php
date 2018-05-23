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
        $companyUserResponseTransfer = (new CompanyUserResponseTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setIsSuccessful(true);

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
        $companyUserResponseTransfer = (new CompanyUserResponseTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setIsSuccessful(true);

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
        $companyResponseTransfer->getCompanyTransfer()->requireIdCompany();
        $companyResponseTransfer->getCompanyTransfer()->requireInitialUserTransfer();

        $companyTransfer = $companyResponseTransfer->getCompanyTransfer();
        $companyUserTransfer = $companyTransfer->getInitialUserTransfer();
        $companyUserTransfer->setFkCompany($companyTransfer->getIdCompany());
        $companyUserResponseTransfer = $this->create($companyUserTransfer);

        $companyResponseTransfer
            ->getCompanyTransfer()
            ->setInitialUserTransfer(
                $companyUserResponseTransfer->getCompanyUser()
            );

        if ($companyUserResponseTransfer->getIsSuccessful() !== true) {
            $companyResponseTransfer->setIsSuccessful(false);
            $this->addMessagesToCompanyResponse(
                $companyUserResponseTransfer->getMessages(),
                $companyResponseTransfer
            );
        }

        return $companyResponseTransfer;
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
}
