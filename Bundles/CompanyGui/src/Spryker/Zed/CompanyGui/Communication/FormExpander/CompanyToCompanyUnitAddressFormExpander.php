<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyGui\Communication\FormExpander;

use Spryker\Zed\CompanyGui\Communication\Form\DataProvider\CompanyToCompanyUnitAddressFormDataProvider;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;

class CompanyToCompanyUnitAddressFormExpander implements CompanyToCompanyUnitAddressFormExpanderInterface
{
    /**
     * @var \Symfony\Component\Form\FormTypeInterface
     */
    protected $companyToCompanyUnitAddressForm;

    /**
     * @var \Spryker\Zed\CompanyGui\Communication\Form\DataProvider\CompanyToCompanyUnitAddressFormDataProvider
     */
    protected $companyToCompanyUnitAddressFormDataProvider;

    /**
     * @param \Symfony\Component\Form\FormTypeInterface $companyToCompanyUnitAddressForm
     * @param \Spryker\Zed\CompanyGui\Communication\Form\DataProvider\CompanyToCompanyUnitAddressFormDataProvider $companyToCompanyUnitAddressFormDataProvider
     */
    public function __construct(
        FormTypeInterface $companyToCompanyUnitAddressForm,
        CompanyToCompanyUnitAddressFormDataProvider $companyToCompanyUnitAddressFormDataProvider
    ) {
        $this->companyToCompanyUnitAddressForm = $companyToCompanyUnitAddressForm;
        $this->companyToCompanyUnitAddressFormDataProvider = $companyToCompanyUnitAddressFormDataProvider;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expand(FormBuilderInterface $builder): FormBuilderInterface
    {
        $idCompany = $builder->getData()->getFkCompany();

        $this->companyToCompanyUnitAddressForm->buildForm(
            $builder,
            $this->companyToCompanyUnitAddressFormDataProvider->getOptions($idCompany),
        );

        return $builder;
    }
}
