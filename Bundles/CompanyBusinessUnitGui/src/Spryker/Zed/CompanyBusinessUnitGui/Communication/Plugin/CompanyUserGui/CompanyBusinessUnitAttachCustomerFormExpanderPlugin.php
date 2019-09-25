<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitGui\Communication\Plugin\CompanyUserGui;

use Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserAttachCustomerFormExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitGui\Communication\CompanyBusinessUnitGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CompanyBusinessUnitGui\Business\CompanyBusinessUnitGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\CompanyBusinessUnitGui\CompanyBusinessUnitGuiConfig getConfig()
 */
class CompanyBusinessUnitAttachCustomerFormExpanderPlugin extends AbstractPlugin implements CompanyUserAttachCustomerFormExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     *  - Expands CustomerCompanyAttachForm with choice field of company business units form CompanyUserBusinessUnitChoiceFormType.
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
            ->createCompanyUserBusinessUnitForm();

        $dataProvider = $this->getFactory()
            ->createCompanyUserBusinessUnitFormDataProvider();

        $formType->buildForm(
            $builder,
            $dataProvider->getOptions()
        );

        return $builder;
    }
}
