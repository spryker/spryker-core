<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitGui\Communication\FormExpander;

use Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\DataProvider\CompanyBusinessUnitToCustomerCompanyAttachFormDataProvider;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;

class CompanyBusinessUnitToCustomerCompanyAttachFormExpander implements CompanyBusinessUnitToCustomerCompanyAttachFormExpanderInterface
{
    /**
     * @var \Symfony\Component\Form\FormTypeInterface
     */
    protected $companyBusinessUnitToCustomerCompanyAttachForm;

    /**
     * @var \Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\DataProvider\CompanyBusinessUnitToCustomerCompanyAttachFormDataProvider
     */
    protected $companyBusinessUnitToCustomerCompanyAttachFormDataProvider;

    /**
     * @param \Symfony\Component\Form\FormTypeInterface $companyBusinessUnitToCustomerCompanyAttachForm
     * @param \Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\DataProvider\CompanyBusinessUnitToCustomerCompanyAttachFormDataProvider $companyBusinessUnitToCustomerCompanyAttachFormDataProvider
     */
    public function __construct(
        FormTypeInterface $companyBusinessUnitToCustomerCompanyAttachForm,
        CompanyBusinessUnitToCustomerCompanyAttachFormDataProvider $companyBusinessUnitToCustomerCompanyAttachFormDataProvider
    ) {
        $this->companyBusinessUnitToCustomerCompanyAttachForm = $companyBusinessUnitToCustomerCompanyAttachForm;
        $this->companyBusinessUnitToCustomerCompanyAttachFormDataProvider = $companyBusinessUnitToCustomerCompanyAttachFormDataProvider;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expand(FormBuilderInterface $builder): FormBuilderInterface
    {
        $this->companyBusinessUnitToCustomerCompanyAttachForm->buildForm(
            $builder,
            $this->companyBusinessUnitToCustomerCompanyAttachFormDataProvider->getOptions(),
        );

        return $builder;
    }
}
