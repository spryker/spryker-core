<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Company\Business\Model;

use Generated\Shared\Transfer\CompanyResponseTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\Company\Persistence\CompanyWriterRepositoryInterface;

class CompanyWriter implements CompanyWriterInterface
{
    /**
     * @var \Spryker\Zed\Company\Persistence\CompanyWriterRepositoryInterface
     */
    protected $companyWriterRepository;

    /**
     * @var \Spryker\Zed\Company\Business\Model\CompanyPluginExecutorInterface
     */
    protected $companyPluginExecutor;

    /**
     * @var \Spryker\Zed\Company\Business\Model\CompanyStoreRelationWriterInterface
     */
    protected $companyStoreRelationWriter;

    /**
     * @param \Spryker\Zed\Company\Persistence\CompanyWriterRepositoryInterface $companyWriterRepository
     * @param \Spryker\Zed\Company\Business\Model\CompanyStoreRelationWriterInterface $companyStoreRelationWriter
     * @param \Spryker\Zed\Company\Business\Model\CompanyPluginExecutorInterface $companyPluginExecutor
     */
    public function __construct(
        CompanyWriterRepositoryInterface $companyWriterRepository,
        CompanyStoreRelationWriterInterface $companyStoreRelationWriter,
        CompanyPluginExecutorInterface $companyPluginExecutor
    ) {
        $this->companyWriterRepository = $companyWriterRepository;
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
        $companyResponseTransfer = $this->save($companyTransfer);
        $companyResponseTransfer = $this->executePostCreatePlugins($companyResponseTransfer);

        return $companyResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyResponseTransfer
     */
    public function update(CompanyTransfer $companyTransfer): CompanyResponseTransfer
    {
        return $this->save($companyTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return void
     */
    public function delete(CompanyTransfer $companyTransfer): void
    {
        $this->companyWriterRepository->delete($companyTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyResponseTransfer
     */
    protected function save(CompanyTransfer $companyTransfer): CompanyResponseTransfer
    {
        $companyTransfer = $this->companyPluginExecutor->executeCompanyPreSavePlugins($companyTransfer);
        $companyTransfer = $this->companyWriterRepository->save($companyTransfer);

        $this->persistCompanyStoreRelation($companyTransfer);

        $companyResponseTransfer = new CompanyResponseTransfer();
        $companyResponseTransfer->setCompanyTransfer($companyTransfer);
        $companyResponseTransfer->setIsSuccessful(true);

        return $companyResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyResponseTransfer $companyResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyResponseTransfer
     */
    protected function executePostCreatePlugins(CompanyResponseTransfer $companyResponseTransfer): CompanyResponseTransfer
    {
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
    protected function persistCompanyStoreRelation(CompanyTransfer $companyTransfer): void
    {
        if ($companyTransfer->getStoreRelation() === null) {
            $companyTransfer->setStoreRelation(new StoreRelationTransfer());
        }

        $companyTransfer->getStoreRelation()->setIdEntity($companyTransfer->getIdCompany());
        $this->companyStoreRelationWriter->save($companyTransfer->getStoreRelation());
    }
}
