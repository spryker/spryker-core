<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Company\Business;

use Spryker\Zed\Company\Business\Model\CompanyPluginExecutor;
use Spryker\Zed\Company\Business\Model\CompanyPluginExecutorInterface;
use Spryker\Zed\Company\Business\Model\CompanyReader;
use Spryker\Zed\Company\Business\Model\CompanyReaderInterface;
use Spryker\Zed\Company\Business\Model\CompanyStoreRelationReader;
use Spryker\Zed\Company\Business\Model\CompanyStoreRelationReaderInterface;
use Spryker\Zed\Company\Business\Model\CompanyStoreRelationWriter;
use Spryker\Zed\Company\Business\Model\CompanyStoreRelationWriterInterface;
use Spryker\Zed\Company\Business\Model\CompanyWriter;
use Spryker\Zed\Company\Business\Model\CompanyWriterInterface;
use Spryker\Zed\Company\CompanyDependencyProvider;
use Spryker\Zed\Company\Dependency\Facade\CompanyToStoreFacadeInterface;
use Spryker\Zed\Company\Persistence\CompanyPersistenceFactory;
use Spryker\Zed\Company\Persistence\CompanyRepositoryInterface;
use Spryker\Zed\Company\Persistence\CompanyWriterRepositoryInterface;
use Spryker\Zed\Company\Persistence\Propel\CompanyPropelRepository;
use Spryker\Zed\Company\Persistence\Propel\CompanyWriterPropelRepository;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Kernel\Persistence\PersistenceFactoryInterface;

/**
 * @method \Spryker\Zed\Company\Persistence\CompanyQueryContainerInterface getQueryContainer()
 */
class CompanyBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Company\Business\Model\CompanyReaderInterface
     */
    public function createCompanyReader(): CompanyReaderInterface
    {
        return new CompanyReader($this->createCompanyRepository());
    }

    /**
     * @return \Spryker\Zed\Company\Business\Model\CompanyWriterInterface
     */
    public function createCompanyWriter(): CompanyWriterInterface
    {
        return new CompanyWriter(
            $this->createCompanyWriterRepository(),
            $this->createStoreRelationWriter(),
            $this->createPluginExecutor()
        );
    }

    /**
     * @return \Spryker\Zed\Company\Business\Model\CompanyStoreRelationWriterInterface
     */
    protected function createStoreRelationWriter(): CompanyStoreRelationWriterInterface
    {
        return new CompanyStoreRelationWriter(
            $this->createCompanyWriterRepository(),
            $this->createCompanyStoreRelationReader()
        );
    }

    /**
     * @return \Spryker\Zed\Company\Business\Model\CompanyStoreRelationReaderInterface
     */
    protected function createCompanyStoreRelationReader(): CompanyStoreRelationReaderInterface
    {
        return new CompanyStoreRelationReader($this->createCompanyRepository());
    }

    /**
     * @TODO Remove this. It should be locatable through Spryker infrastructure (CompanyEntityManager?).
     *
     * @return \Spryker\Zed\Company\Persistence\CompanyWriterRepositoryInterface
     */
    protected function createCompanyWriterRepository(): CompanyWriterRepositoryInterface
    {
        return new CompanyWriterPropelRepository();
    }

    /**
     * @return \Spryker\Zed\Company\Business\Model\CompanyPluginExecutorInterface
     */
    protected function createPluginExecutor(): CompanyPluginExecutorInterface
    {
        return new CompanyPluginExecutor(
            $this->getCompanyPreSavePlugins(),
            $this->getCompanyPostCreatePlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Company\Dependency\Facade\CompanyToStoreFacadeInterface
     */
    protected function getStoreFacade(): CompanyToStoreFacadeInterface
    {
        return $this->getProvidedDependency(CompanyDependencyProvider::FACADE_STORE);
    }

    /**
     * @TODO Remove this.
     *
     * @return \Spryker\Zed\Kernel\Persistence\PersistenceFactoryInterface
     */
    protected function createCompanyPersistenceFactory(): PersistenceFactoryInterface
    {
        return new CompanyPersistenceFactory();
    }

    /**
     * @TODO Remove this. It should be locatable through Spryker infrastructure.
     *
     * @return \Spryker\Zed\Company\Persistence\CompanyRepositoryInterface
     */
    protected function createCompanyRepository(): CompanyRepositoryInterface
    {
        return new CompanyPropelRepository();
    }

    /**
     * @return \Spryker\Zed\Company\Dependency\Plugin\CompanyPreSavePluginInterface[]
     */
    protected function getCompanyPreSavePlugins(): array
    {
        return $this->getProvidedDependency(CompanyDependencyProvider::COMPANY_PRE_SAVE_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\Company\Dependency\Plugin\CompanyPostCreatePluginInterface[]
     */
    protected function getCompanyPostCreatePlugins(): array
    {
        return $this->getProvidedDependency(CompanyDependencyProvider::COMPANY_POST_CREATE_PLUGINS);
    }
}
