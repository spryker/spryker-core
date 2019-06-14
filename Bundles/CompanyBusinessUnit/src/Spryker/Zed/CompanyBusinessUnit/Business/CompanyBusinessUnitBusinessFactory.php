<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Business;

use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitAssigner\CompanyBusinessUnitAssigner;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitCreator\CompanyBusinessUnitCreator;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitCreator\CompanyBusinessUnitCreatorInterface;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFinder\CompanyBusinessUnitReader;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFinder\CompanyBusinessUnitReaderInterface;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitPluginExecutor\CompanyBusinessUnitPluginExecutor;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitPluginExecutor\CompanyBusinessUnitPluginExecutorInterface;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitTreeBuilder\CompanyBusinessUnitTreeBuilder;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitTreeBuilder\CompanyBusinessUnitTreeBuilderInterface;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitWriter\CompanyBusinessUnitWriter;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitWriter\CompanyBusinessUnitWriterInterface;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyUserValidator\CompanyUserValidator;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyUserValidator\CompanyUserValidatorInterface;
use Spryker\Zed\CompanyBusinessUnit\CompanyBusinessUnitDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface getRepository()
 * @method \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CompanyBusinessUnit\CompanyBusinessUnitConfig getConfig()
 */
class CompanyBusinessUnitBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitWriter\CompanyBusinessUnitWriterInterface
     */
    public function createCompanyBusinessUnitWriter(): CompanyBusinessUnitWriterInterface
    {
        return new CompanyBusinessUnitWriter(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->createCompanyBusinessUnitPluginExecutor()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitCreator\CompanyBusinessUnitCreatorInterface
     */
    public function createCompanyBusinessUnitCreator(): CompanyBusinessUnitCreatorInterface
    {
        return new CompanyBusinessUnitCreator(
            $this->getEntityManager(),
            $this->getConfig(),
            $this->createCompanyBusinessUnitPluginExecutor()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitAssigner\CompanyBusinessUnitAssignerInterface
     */
    public function createCompanyBusinessUnitAssigner()
    {
        return new CompanyBusinessUnitAssigner(
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitPluginExecutor\CompanyBusinessUnitPluginExecutorInterface
     */
    public function createCompanyBusinessUnitPluginExecutor(): CompanyBusinessUnitPluginExecutorInterface
    {
        return new CompanyBusinessUnitPluginExecutor(
            $this->getCompanyBusinessUnitExpanderPlugins(),
            $this->getCompanyBusinessUnitPostSavePlugins(),
            $this->getCompanyBusinessUnitPreDeletePlugins()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFinder\CompanyBusinessUnitReaderInterface
     */
    public function createCompanyBusinessUnitReader(): CompanyBusinessUnitReaderInterface
    {
        return new CompanyBusinessUnitReader(
            $this->getRepository(),
            $this->createCompanyBusinessUnitPluginExecutor()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitExtension\Dependency\Plugin\CompanyBusinessUnitPostSavePluginInterface[]
     */
    protected function getCompanyBusinessUnitPostSavePlugins(): array
    {
        return $this->getProvidedDependency(CompanyBusinessUnitDependencyProvider::PLUGINS_COMPANY_BUSINESS_UNIT_POST_SAVE);
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitExtension\Dependency\Plugin\CompanyBusinessUnitPreDeletePluginInterface[]
     */
    protected function getCompanyBusinessUnitPreDeletePlugins(): array
    {
        return $this->getProvidedDependency(CompanyBusinessUnitDependencyProvider::PLUGINS_COMPANY_BUSINESS_UNIT_PRE_DELETE);
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitTreeBuilder\CompanyBusinessUnitTreeBuilderInterface
     */
    public function createCompanyBusinessUnitTreeBuilder(): CompanyBusinessUnitTreeBuilderInterface
    {
        return new CompanyBusinessUnitTreeBuilder(
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitExtension\Dependency\Plugin\CompanyBusinessUnitExpanderPluginInterface[]
     */
    public function getCompanyBusinessUnitExpanderPlugins(): array
    {
        return $this->getProvidedDependency(CompanyBusinessUnitDependencyProvider::PLUGINS_COMPANY_BUSINESS_UNIT_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnit\Business\CompanyUserValidator\CompanyUserValidatorInterface
     */
    public function createCompanyUserValidator(): CompanyUserValidatorInterface
    {
        return new CompanyUserValidator(
            $this->getRepository()
        );
    }
}
