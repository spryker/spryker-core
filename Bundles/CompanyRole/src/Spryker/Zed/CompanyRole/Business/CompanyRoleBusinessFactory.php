<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Business;

use Spryker\Zed\CompanyRole\Business\Model\CompanyRoleWriter;
use Spryker\Zed\CompanyRole\Business\Model\CompanyRoleWriterInterface;
use Spryker\Zed\CompanyRole\Persistence\CompanyRolePersistenceFactory;
use Spryker\Zed\CompanyRole\Persistence\CompanyRoleRepository;
use Spryker\Zed\CompanyRole\Persistence\CompanyRoleRepositoryInterface;
use Spryker\Zed\CompanyRole\Persistence\CompanyRoleWriterRepository;
use Spryker\Zed\CompanyRole\Persistence\CompanyRoleWriterRepositoryInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Kernel\Persistence\PersistenceFactoryInterface;

/**
 * @method \Spryker\Zed\CompanyRole\Persistence\CompanyRoleQueryContainerInterface getQueryContainer()
 */
class CompanyRoleBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CompanyRole\Business\Model\CompanyRoleWriterInterface
     */
    public function createCompanyRoleWriter(): CompanyRoleWriterInterface
    {
        return new CompanyRoleWriter($this->createCompanyRoleWriterRepository());
    }

    /**
     * @return \Spryker\Zed\CompanyRole\Persistence\CompanyRoleWriterRepositoryInterface
     */
    public function createCompanyRoleWriterRepository(): CompanyRoleWriterRepositoryInterface
    {
        $companyRoleWriterRepository = new CompanyRoleWriterRepository();
        $companyRoleWriterRepository->setQueryContainer($this->getQueryContainer());
        $companyRoleWriterRepository->setPersistenceFactory($this->createPersistenceFactory());

        return $companyRoleWriterRepository;
    }

    /**
     * @return \Spryker\Zed\CompanyRole\Persistence\CompanyRoleRepositoryInterface
     */
    public function createCompanyRoleRepository(): CompanyRoleRepositoryInterface
    {
        return new CompanyRoleRepository();
    }

    /**
     * @return \Spryker\Zed\Kernel\Persistence\PersistenceFactoryInterface
     */
    protected function createPersistenceFactory(): PersistenceFactoryInterface
    {
        return new CompanyRolePersistenceFactory();
    }
}
