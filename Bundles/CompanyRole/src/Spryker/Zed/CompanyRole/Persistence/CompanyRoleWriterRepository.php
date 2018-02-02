<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Persistence;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

class CompanyRoleWriterRepository implements CompanyRoleWriterRepositoryInterface, SprykerAwareRepositoryInterface
{
    /**
     * @var \Spryker\Zed\CompanyRole\Persistence\CompanyRoleQueryContainerInterface
     */
    protected $companyRoleQueryContainer;

    /**
     * @var \Spryker\Zed\CompanyRole\Persistence\CompanyRolePersistenceFactory
     */
    protected $persistenceFactory;

    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    public function save(CompanyRoleTransfer $companyRoleTransfer): CompanyRoleTransfer
    {
        $companyRoleEntity = $this->persistenceFactory->createCompanyRoleMapper()->mapTransferToCompanyRoleEntity($companyRoleTransfer);
        $companyRoleEntity->save();
        $companyRoleTransfer = $this->persistenceFactory->createCompanyRoleMapper()->mapCompanyRoleEntityToTransfer($companyRoleEntity);

        return $companyRoleTransfer;
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated
     *
     * @param \Spryker\Zed\CompanyRole\Persistence\CompanyRoleQueryContainerInterface $companyRoleQueryContainer
     *
     * @return \Spryker\Zed\CompanyRole\Persistence\CompanyRoleWriterRepository
     */
    public function setQueryContainer(AbstractQueryContainer $companyRoleQueryContainer): CompanyRoleWriterRepository
    {
        $this->companyRoleQueryContainer = $companyRoleQueryContainer;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated
     *
     * @param \Spryker\Zed\CompanyRole\Persistence\CompanyRolePersistenceFactory $persistenceFactory
     *
     * @return \Spryker\Zed\CompanyRole\Persistence\CompanyRoleWriterRepository
     */
    public function setPersistenceFactory(AbstractPersistenceFactory $persistenceFactory): CompanyRoleWriterRepository
    {
        $this->persistenceFactory = $persistenceFactory;

        return $this;
    }
}
