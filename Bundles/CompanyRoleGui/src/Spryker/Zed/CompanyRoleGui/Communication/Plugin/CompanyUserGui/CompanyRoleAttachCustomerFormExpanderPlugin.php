<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRoleGui\Communication\Plugin\CompanyUserGui;

use Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserAttachCustomerFormExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\CompanyRoleGui\Communication\CompanyRoleGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CompanyRoleGui\CompanyRoleGuiConfig getConfig()
 */
class CompanyRoleAttachCustomerFormExpanderPlugin extends AbstractPlugin implements CompanyUserAttachCustomerFormExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     *  - Expands CustomerCompanyAttachForm with choice field of company roles form CompanyUserRoleChoiceFormType.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expand(FormBuilderInterface $builder): FormBuilderInterface
    {
        $formType = $this->getFactory()
            ->createCompanyUserRoleForm();

        $dataProvider = $this->getFactory()
            ->createCompanyUserRoleFormDataProvider();

        $companyUserTransfer = $builder->getData();
        $formType->buildForm(
            $builder,
            $dataProvider->getOptions($companyUserTransfer)
        );

        return $builder;
    }
}
