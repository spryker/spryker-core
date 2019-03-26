<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\CompanyRoleGui\Communication\Plugin\BusinessOnBehalfGui;

use Spryker\Zed\BusinessOnBehalfGuiExtension\Dependency\Plugin\CustomerBusinessUnitAttachFormExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\CompanyRoleGui\Communication\CompanyRoleGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CompanyRoleGui\CompanyRoleGuiConfig getConfig()
 */
class CompanyRoleCustomerBusinessUnitAttachFormExpanderPlugin extends AbstractPlugin implements CustomerBusinessUnitAttachFormExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     * - Expands CustomerBusinessUnitAttachForm with choice field of company roles form CompanyUserRoleChoiceFormType.
     * - Returns CustomerBusinessUnitAttachForm without expanding if Company does not exist in CompanyUserTransfer.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expand(FormBuilderInterface $builder): FormBuilderInterface
    {
        $companyUserTransfer = $builder->getData();

        if (!$companyUserTransfer->getCompany()) {
            return $builder;
        }

        $companyUserRoleByCompanyForm = $this->getFactory()
            ->createCompanyUserRoleByCompanyForm();

        $dataProvider = $this->getFactory()
            ->createCompanyUserRoleFormDataProviderByCompany();

        $companyUserTransfer = $dataProvider->getData($companyUserTransfer);
        $builder->setData($companyUserTransfer);

        $companyUserRoleByCompanyForm->buildForm(
            $builder,
            $dataProvider->getOptions($companyUserTransfer)
        );

        return $builder;
    }
}
