<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitGui\Communication;

use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\CompanyBusinessUnitEditForm;
use Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\CompanyBusinessUnitForm;
use Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\CompanyBusinessUnitToCompanyUserForm;
use Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\CompanyBusinessUnitToCustomerBusinessUnitAttachForm;
use Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\CompanyBusinessUnitToCustomerCompanyAttachForm;
use Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\CompanyUserBusinessUnitForm;
use Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\DataProvider\CompanyBusinessUnitFormDataProvider;
use Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\DataProvider\CompanyBusinessUnitToCompanyUserFormDataProvider;
use Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\DataProvider\CompanyBusinessUnitToCustomerBusinessUnitAttachFormDataProvider;
use Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\DataProvider\CompanyBusinessUnitToCustomerCompanyAttachFormDataProvider;
use Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\DataProvider\CompanyUserBusinessUnitFormDataProvider;
use Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\DeleteCompanyBusinessUnitForm;
use Spryker\Zed\CompanyBusinessUnitGui\Communication\Formatter\CompanyBusinessUnitGuiFormatter;
use Spryker\Zed\CompanyBusinessUnitGui\Communication\Formatter\CompanyBusinessUnitGuiFormatterInterface;
use Spryker\Zed\CompanyBusinessUnitGui\Communication\FormExpander\CompanyBusinessUnitToCompanyUserFormExpander;
use Spryker\Zed\CompanyBusinessUnitGui\Communication\FormExpander\CompanyBusinessUnitToCompanyUserFormExpanderInterface;
use Spryker\Zed\CompanyBusinessUnitGui\Communication\FormExpander\CompanyBusinessUnitToCustomerBusinessUnitAttachFormExpander;
use Spryker\Zed\CompanyBusinessUnitGui\Communication\FormExpander\CompanyBusinessUnitToCustomerBusinessUnitAttachFormExpanderInterface;
use Spryker\Zed\CompanyBusinessUnitGui\Communication\FormExpander\CompanyBusinessUnitToCustomerCompanyAttachFormExpander;
use Spryker\Zed\CompanyBusinessUnitGui\Communication\FormExpander\CompanyBusinessUnitToCustomerCompanyAttachFormExpanderInterface;
use Spryker\Zed\CompanyBusinessUnitGui\Communication\Generator\CompanyBusinessUnitNameGenerator;
use Spryker\Zed\CompanyBusinessUnitGui\Communication\Generator\CompanyBusinessUnitNameGeneratorInterface;
use Spryker\Zed\CompanyBusinessUnitGui\Communication\Table\CompanyBusinessUnitTable;
use Spryker\Zed\CompanyBusinessUnitGui\CompanyBusinessUnitGuiDependencyProvider;
use Spryker\Zed\CompanyBusinessUnitGui\Dependency\Facade\CompanyBusinessUnitGuiToCompanyBusinessUnitFacadeInterface;
use Spryker\Zed\CompanyBusinessUnitGui\Dependency\Facade\CompanyBusinessUnitGuiToCompanyFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitGui\CompanyBusinessUnitGuiConfig getConfig()
 * @method \Spryker\Zed\CompanyBusinessUnitGui\Business\CompanyBusinessUnitGuiFacadeInterface getFacade()
 */
class CompanyBusinessUnitGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CompanyBusinessUnitGui\Communication\Table\CompanyBusinessUnitTable
     */
    public function createCompanyBusinessUnitTable(): CompanyBusinessUnitTable
    {
        return new CompanyBusinessUnitTable(
            $this->getCompanyBusinessUnitQuery(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer|null $data
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCompanyBusinessUnitForm($data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(CompanyBusinessUnitForm::class, $data, $options);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer|null $data
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCompanyBusinessUnitEditForm($data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(CompanyBusinessUnitEditForm::class, $data, $options);
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\DataProvider\CompanyBusinessUnitFormDataProvider
     */
    public function createCompanyBusinessUnitFormDataProvider(): CompanyBusinessUnitFormDataProvider
    {
        return new CompanyBusinessUnitFormDataProvider(
            $this->getCompanyBusinessUnitFacade(),
            $this->getCompanyFacade(),
        );
    }

    /**
     * @return \Symfony\Component\Form\FormTypeInterface
     */
    public function createCompanyUserBusinessUnitForm(): FormTypeInterface
    {
        return new CompanyUserBusinessUnitForm();
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\DataProvider\CompanyUserBusinessUnitFormDataProvider
     */
    public function createCompanyUserBusinessUnitFormDataProvider(): CompanyUserBusinessUnitFormDataProvider
    {
        return new CompanyUserBusinessUnitFormDataProvider(
            $this->getCompanyBusinessUnitFacade(),
            $this->createCompanyBusinessUnitNameGenerator(),
        );
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitGui\Communication\Formatter\CompanyBusinessUnitGuiFormatterInterface
     */
    public function createCompanyBusinessUnitGuiFormatter(): CompanyBusinessUnitGuiFormatterInterface
    {
        return new CompanyBusinessUnitGuiFormatter($this->createCompanyBusinessUnitNameGenerator());
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createDeleteCompanyBusinessUnitForm(): FormInterface
    {
        return $this->getFormFactory()->create(DeleteCompanyBusinessUnitForm::class, [], [
            'fields' => [],
        ]);
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitGui\Dependency\Facade\CompanyBusinessUnitGuiToCompanyBusinessUnitFacadeInterface
     */
    public function getCompanyBusinessUnitFacade(): CompanyBusinessUnitGuiToCompanyBusinessUnitFacadeInterface
    {
        return $this->getProvidedDependency(CompanyBusinessUnitGuiDependencyProvider::FACADE_COMPANY_BUSINESS_UNIT);
    }

    /**
     * @return \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery
     */
    protected function getCompanyBusinessUnitQuery(): SpyCompanyBusinessUnitQuery
    {
        return $this->getProvidedDependency(CompanyBusinessUnitGuiDependencyProvider::PROPEL_QUERY_COMPANY_BUSINESS_UNIT);
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitGui\Dependency\Facade\CompanyBusinessUnitGuiToCompanyFacadeInterface
     */
    public function getCompanyFacade(): CompanyBusinessUnitGuiToCompanyFacadeInterface
    {
        return $this->getProvidedDependency(CompanyBusinessUnitGuiDependencyProvider::FACADE_COMPANY);
    }

    /**
     * @return array<\Spryker\Zed\CompanyBusinessUnitGuiExtension\Communication\Plugin\CompanyBusinessUnitFormExpanderPluginInterface>
     */
    public function getCompanyBusinessUnitFormPlugins(): array
    {
        return $this->getProvidedDependency(CompanyBusinessUnitGuiDependencyProvider::COMPANY_BUSINESS_UNIT_FORM_EXPANDER_PLUGINS);
    }

    /**
     * @return array<\Spryker\Zed\CompanyBusinessUnitGuiExtension\Communication\Plugin\CompanyBusinessUnitFormExpanderPluginInterface>
     */
    public function getCompanyBusinessUnitEditFormPlugins(): array
    {
        return $this->getProvidedDependency(CompanyBusinessUnitGuiDependencyProvider::COMPANY_BUSINESS_UNIT_EDIT_FORM_EXPANDER_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitGui\Communication\Generator\CompanyBusinessUnitNameGeneratorInterface
     */
    public function createCompanyBusinessUnitNameGenerator(): CompanyBusinessUnitNameGeneratorInterface
    {
        return new CompanyBusinessUnitNameGenerator();
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitGui\Communication\FormExpander\CompanyBusinessUnitToCustomerBusinessUnitAttachFormExpanderInterface
     */
    public function createCompanyBusinessUnitToCustomerBusinessUnitAttachFormExpander(): CompanyBusinessUnitToCustomerBusinessUnitAttachFormExpanderInterface
    {
        return new CompanyBusinessUnitToCustomerBusinessUnitAttachFormExpander(
            $this->createCompanyBusinessUnitToCustomerBusinessUnitAttachForm(),
            $this->createCompanyBusinessUnitToCustomerBusinessUnitAttachFormDataProvider(),
        );
    }

    /**
     * @return \Symfony\Component\Form\FormTypeInterface
     */
    public function createCompanyBusinessUnitToCustomerBusinessUnitAttachForm(): FormTypeInterface
    {
        return new CompanyBusinessUnitToCustomerBusinessUnitAttachForm();
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\DataProvider\CompanyBusinessUnitToCustomerBusinessUnitAttachFormDataProvider
     */
    public function createCompanyBusinessUnitToCustomerBusinessUnitAttachFormDataProvider(): CompanyBusinessUnitToCustomerBusinessUnitAttachFormDataProvider
    {
        return new CompanyBusinessUnitToCustomerBusinessUnitAttachFormDataProvider(
            $this->getCompanyBusinessUnitFacade(),
            $this->createCompanyBusinessUnitNameGenerator(),
        );
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitGui\Communication\FormExpander\CompanyBusinessUnitToCustomerCompanyAttachFormExpanderInterface
     */
    public function createCompanyBusinessUnitToCustomerCompanyAttachFormExpander(): CompanyBusinessUnitToCustomerCompanyAttachFormExpanderInterface
    {
        return new CompanyBusinessUnitToCustomerCompanyAttachFormExpander(
            $this->createCompanyBusinessUnitToCustomerCompanyAttachForm(),
            $this->createCompanyBusinessUnitToCustomerCompanyAttachFormDataProvider(),
        );
    }

    /**
     * @return \Symfony\Component\Form\FormTypeInterface
     */
    public function createCompanyBusinessUnitToCustomerCompanyAttachForm(): FormTypeInterface
    {
        return new CompanyBusinessUnitToCustomerCompanyAttachForm();
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\DataProvider\CompanyBusinessUnitToCustomerCompanyAttachFormDataProvider
     */
    public function createCompanyBusinessUnitToCustomerCompanyAttachFormDataProvider(): CompanyBusinessUnitToCustomerCompanyAttachFormDataProvider
    {
        return new CompanyBusinessUnitToCustomerCompanyAttachFormDataProvider(
            $this->getCompanyBusinessUnitFacade(),
            $this->createCompanyBusinessUnitNameGenerator(),
        );
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitGui\Communication\FormExpander\CompanyBusinessUnitToCompanyUserFormExpanderInterface
     */
    public function createCompanyBusinessUnitToCompanyUserFormExpander(): CompanyBusinessUnitToCompanyUserFormExpanderInterface
    {
        return new CompanyBusinessUnitToCompanyUserFormExpander(
            $this->createCompanyBusinessUnitToCompanyUserForm(),
            $this->createCompanyBusinessUnitToCompanyUserFormDataProvider(),
        );
    }

    /**
     * @return \Symfony\Component\Form\FormTypeInterface
     */
    public function createCompanyBusinessUnitToCompanyUserForm(): FormTypeInterface
    {
        return new CompanyBusinessUnitToCompanyUserForm();
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\DataProvider\CompanyBusinessUnitToCompanyUserFormDataProvider
     */
    public function createCompanyBusinessUnitToCompanyUserFormDataProvider(): CompanyBusinessUnitToCompanyUserFormDataProvider
    {
        return new CompanyBusinessUnitToCompanyUserFormDataProvider(
            $this->getCompanyBusinessUnitFacade(),
            $this->createCompanyBusinessUnitNameGenerator(),
        );
    }
}
