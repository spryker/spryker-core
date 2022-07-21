<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyGui\Communication\FormExpander;

use Spryker\Zed\CompanyGui\Communication\Form\DataProvider\CompanyToCompanyBusinessUnitFormDataProvider;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;

class CompanyToCompanyBusinessUnitFormExpander implements CompanyToCompanyBusinessUnitFormExpanderInterface
{
    /**
     * @var \Symfony\Component\Form\FormTypeInterface
     */
    protected $companyToCompanyBusinessUnitForm;

    /**
     * @var \Spryker\Zed\CompanyGui\Communication\Form\DataProvider\CompanyToCompanyBusinessUnitFormDataProvider
     */
    protected $companyToCompanyBusinessUnitFormDataProvider;

    /**
     * @param \Symfony\Component\Form\FormTypeInterface $companyToCompanyBusinessUnitForm
     * @param \Spryker\Zed\CompanyGui\Communication\Form\DataProvider\CompanyToCompanyBusinessUnitFormDataProvider $companyToCompanyBusinessUnitFormDataProvider
     */
    public function __construct(
        FormTypeInterface $companyToCompanyBusinessUnitForm,
        CompanyToCompanyBusinessUnitFormDataProvider $companyToCompanyBusinessUnitFormDataProvider
    ) {
        $this->companyToCompanyBusinessUnitForm = $companyToCompanyBusinessUnitForm;
        $this->companyToCompanyBusinessUnitFormDataProvider = $companyToCompanyBusinessUnitFormDataProvider;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expand(FormBuilderInterface $builder): FormBuilderInterface
    {
        $idCompany = $builder->getData()->getFkCompany();

        $this->companyToCompanyBusinessUnitForm->buildForm(
            $builder,
            $this->companyToCompanyBusinessUnitFormDataProvider->getOptions($idCompany),
        );

        return $builder;
    }
}
