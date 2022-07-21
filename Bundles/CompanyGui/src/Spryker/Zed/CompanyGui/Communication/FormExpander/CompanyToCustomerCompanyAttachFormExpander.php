<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyGui\Communication\FormExpander;

use Spryker\Zed\CompanyGui\Communication\Form\DataProvider\CompanyToCustomerCompanyAttachFormDataProvider;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;

class CompanyToCustomerCompanyAttachFormExpander implements CompanyToCustomerCompanyAttachFormExpanderInterface
{
    /**
     * @var \Symfony\Component\Form\FormTypeInterface
     */
    protected $companyToCustomerCompanyAttachForm;

    /**
     * @var \Spryker\Zed\CompanyGui\Communication\Form\DataProvider\CompanyToCustomerCompanyAttachFormDataProvider
     */
    protected $companyToCustomerCompanyAttachFormDataProvider;

    /**
     * @param \Symfony\Component\Form\FormTypeInterface $companyToCompanyBusinessUnitForm
     * @param \Spryker\Zed\CompanyGui\Communication\Form\DataProvider\CompanyToCustomerCompanyAttachFormDataProvider $companyToCustomerCompanyAttachFormDataProvider
     */
    public function __construct(
        FormTypeInterface $companyToCompanyBusinessUnitForm,
        CompanyToCustomerCompanyAttachFormDataProvider $companyToCustomerCompanyAttachFormDataProvider
    ) {
        $this->companyToCustomerCompanyAttachForm = $companyToCompanyBusinessUnitForm;
        $this->companyToCustomerCompanyAttachFormDataProvider = $companyToCustomerCompanyAttachFormDataProvider;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expand(FormBuilderInterface $builder): FormBuilderInterface
    {
        $idCompany = $builder->getData()->getFkCompany();

        $this->companyToCustomerCompanyAttachForm->buildForm(
            $builder,
            $this->companyToCustomerCompanyAttachFormDataProvider->getOptions($idCompany),
        );

        return $builder;
    }
}
