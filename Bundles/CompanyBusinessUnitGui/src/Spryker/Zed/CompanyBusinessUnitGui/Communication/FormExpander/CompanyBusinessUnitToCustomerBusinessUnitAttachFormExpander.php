<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitGui\Communication\FormExpander;

use Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\DataProvider\CompanyBusinessUnitToCustomerBusinessUnitAttachFormDataProvider;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;

class CompanyBusinessUnitToCustomerBusinessUnitAttachFormExpander implements CompanyBusinessUnitToCustomerBusinessUnitAttachFormExpanderInterface
{
    /**
     * @var \Symfony\Component\Form\FormTypeInterface
     */
    protected $companyBusinessUnitToCustomerBusinessUnitAttachForm;

    /**
     * @var \Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\DataProvider\CompanyBusinessUnitToCustomerBusinessUnitAttachFormDataProvider
     */
    protected $companyBusinessUnitToCustomerBusinessUnitAttachFormDataProvider;

    /**
     * @param \Symfony\Component\Form\FormTypeInterface $companyBusinessUnitToCustomerBusinessUnitAttachForm
     * @param \Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\DataProvider\CompanyBusinessUnitToCustomerBusinessUnitAttachFormDataProvider $companyBusinessUnitToCustomerBusinessUnitAttachFormDataProvider
     */
    public function __construct(
        FormTypeInterface $companyBusinessUnitToCustomerBusinessUnitAttachForm,
        CompanyBusinessUnitToCustomerBusinessUnitAttachFormDataProvider $companyBusinessUnitToCustomerBusinessUnitAttachFormDataProvider
    ) {
        $this->companyBusinessUnitToCustomerBusinessUnitAttachForm = $companyBusinessUnitToCustomerBusinessUnitAttachForm;
        $this->companyBusinessUnitToCustomerBusinessUnitAttachFormDataProvider = $companyBusinessUnitToCustomerBusinessUnitAttachFormDataProvider;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expand(FormBuilderInterface $builder): FormBuilderInterface
    {
        $idCompanyBusinessUnit = $builder->getData()->getFkCompanyBusinessUnit();

        $this->companyBusinessUnitToCustomerBusinessUnitAttachForm->buildForm(
            $builder,
            $this->companyBusinessUnitToCustomerBusinessUnitAttachFormDataProvider->getOptions($idCompanyBusinessUnit),
        );

        return $builder;
    }
}
