<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitGui\Communication\FormExpander;

use Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\DataProvider\CompanyBusinessUnitToCompanyUserFormDataProvider;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;

class CompanyBusinessUnitToCompanyUserFormExpander implements CompanyBusinessUnitToCompanyUserFormExpanderInterface
{
    /**
     * @var \Symfony\Component\Form\FormTypeInterface
     */
    protected $companyBusinessUnitToCompanyUserForm;

    /**
     * @var \Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\DataProvider\CompanyBusinessUnitToCompanyUserFormDataProvider
     */
    protected $companyBusinessUnitToCompanyUserFormDataProvider;

    /**
     * @param \Symfony\Component\Form\FormTypeInterface $companyBusinessUnitToCompanyUserForm
     * @param \Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\DataProvider\CompanyBusinessUnitToCompanyUserFormDataProvider $companyBusinessUnitToCompanyUserFormDataProvider
     */
    public function __construct(
        FormTypeInterface $companyBusinessUnitToCompanyUserForm,
        CompanyBusinessUnitToCompanyUserFormDataProvider $companyBusinessUnitToCompanyUserFormDataProvider
    ) {
        $this->companyBusinessUnitToCompanyUserForm = $companyBusinessUnitToCompanyUserForm;
        $this->companyBusinessUnitToCompanyUserFormDataProvider = $companyBusinessUnitToCompanyUserFormDataProvider;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expand(FormBuilderInterface $builder): FormBuilderInterface
    {
        $idCompanyBusinessUnit = $builder->getData()->getFkCompanyBusinessUnit();

        $this->companyBusinessUnitToCompanyUserForm->buildForm(
            $builder,
            $this->companyBusinessUnitToCompanyUserFormDataProvider->getOptions($idCompanyBusinessUnit),
        );

        return $builder;
    }
}
