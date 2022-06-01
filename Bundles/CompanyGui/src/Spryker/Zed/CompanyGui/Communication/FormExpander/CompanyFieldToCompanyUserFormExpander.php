<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyGui\Communication\FormExpander;

use Spryker\Zed\CompanyGui\Communication\Form\DataProvider\CompanyToCompanyUserFormDataProvider;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;

class CompanyFieldToCompanyUserFormExpander implements CompanyFieldToCompanyUserFormExpanderInterface
{
    /**
     * @var \Symfony\Component\Form\FormTypeInterface
     */
    protected $companyToCompanyUserForm;

    /**
     * @var \Spryker\Zed\CompanyGui\Communication\Form\DataProvider\CompanyToCompanyUserFormDataProvider
     */
    protected $companyToCompanyUserFormDataProvider;

    /**
     * @param \Symfony\Component\Form\FormTypeInterface $companyToCompanyUserForm
     * @param \Spryker\Zed\CompanyGui\Communication\Form\DataProvider\CompanyToCompanyUserFormDataProvider $companyToCompanyUserFormDataProvider
     */
    public function __construct(
        FormTypeInterface $companyToCompanyUserForm,
        CompanyToCompanyUserFormDataProvider $companyToCompanyUserFormDataProvider
    ) {
        $this->companyToCompanyUserForm = $companyToCompanyUserForm;
        $this->companyToCompanyUserFormDataProvider = $companyToCompanyUserFormDataProvider;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expand(FormBuilderInterface $builder): FormBuilderInterface
    {
        $idCompany = $builder->getData()->getFkCompany();

        $this->companyToCompanyUserForm->buildForm(
            $builder,
            $this->companyToCompanyUserFormDataProvider->getOptions($idCompany),
        );

        return $builder;
    }
}
