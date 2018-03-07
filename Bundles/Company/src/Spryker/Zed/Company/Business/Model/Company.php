<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Company\Business\Model;

use Generated\Shared\Transfer\CompanyResponseTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Spryker\Zed\Company\Persistence\CompanyEntityManagerInterface;
use Spryker\Zed\Company\Persistence\CompanyRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class Company implements CompanyInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Company\Persistence\CompanyRepositoryInterface
     */
    protected $companyRepository;

    /**
     * @var \Spryker\Zed\Company\Persistence\CompanyEntityManagerInterface
     */
    protected $companyEntityManager;

    /**
     * @var \Spryker\Zed\Company\Business\Model\CompanyPluginExecutorInterface
     */
    protected $companyPluginExecutor;

    /**
     * @var \Spryker\Zed\Company\Business\Model\CompanyStoreRelationWriterInterface
     */
    protected $companyStoreRelationWriter;

    /**
     * @param \Spryker\Zed\Company\Persistence\CompanyRepositoryInterface $companyRepository
     * @param \Spryker\Zed\Company\Persistence\CompanyEntityManagerInterface $companyEntityManager
     * @param \Spryker\Zed\Company\Business\Model\CompanyPluginExecutorInterface $companyPluginExecutor
     * @param \Spryker\Zed\Company\Business\Model\CompanyStoreRelationWriterInterface $companyStoreRelationWriter
     */
    public function __construct(
        CompanyRepositoryInterface $companyRepository,
        CompanyEntityManagerInterface $companyEntityManager,
        CompanyPluginExecutorInterface $companyPluginExecutor,
        CompanyStoreRelationWriterInterface $companyStoreRelationWriter
    ) {
        $this->companyRepository = $companyRepository;
        $this->companyEntityManager = $companyEntityManager;
        $this->companyPluginExecutor = $companyPluginExecutor;
        $this->companyStoreRelationWriter = $companyStoreRelationWriter;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyResponseTransfer
     */
    public function create(CompanyTransfer $companyTransfer): CompanyResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($companyTransfer) {
            $companyResponseTransfer = $this->executeSaveCompanyTransaction($companyTransfer);

            return $this->executePostCreatePlugins($companyResponseTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyResponseTransfer
     */
    public function save(CompanyTransfer $companyTransfer): CompanyResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($companyTransfer) {
            return $this->executeSaveCompanyTransaction($companyTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return void
     */
    public function delete(CompanyTransfer $companyTransfer): void
    {
        $companyTransfer->requireIdCompany();
        $this->companyEntityManager->deleteCompanyById($companyTransfer->getIdCompany());
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyResponseTransfer
     */
    protected function executeSaveCompanyTransaction(CompanyTransfer $companyTransfer): CompanyResponseTransfer
    {
        $companyTransfer = $this->companyPluginExecutor->executeCompanyPreSavePlugins($companyTransfer);
        $companyTransfer = $this->companyEntityManager->saveCompany($companyTransfer);
        $this->persistStoreRelations($companyTransfer);
        $companyTransfer = $this->companyPluginExecutor->executeCompanyPostSavePlugins($companyTransfer);

        return (new CompanyResponseTransfer())
            ->setIsSuccessful(true)
            ->setCompanyTransfer($companyTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyResponseTransfer $companyResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyResponseTransfer
     */
    protected function executePostCreatePlugins(
        CompanyResponseTransfer $companyResponseTransfer
    ): CompanyResponseTransfer {
        $companyTransfer = $companyResponseTransfer->getCompanyTransfer();
        $companyTransfer = $this->companyPluginExecutor->executeCompanyPostCreatePlugins($companyTransfer);
        $companyResponseTransfer->setCompanyTransfer($companyTransfer);

        return $companyResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return void
     */
    protected function persistStoreRelations(CompanyTransfer $companyTransfer): void
    {
        if ($companyTransfer->getStoreRelation() !== null) {
            $companyTransfer->getStoreRelation()->setIdEntity($companyTransfer->getIdCompany());
            $this->companyStoreRelationWriter->save($companyTransfer->getStoreRelation());
        }
    }
}
