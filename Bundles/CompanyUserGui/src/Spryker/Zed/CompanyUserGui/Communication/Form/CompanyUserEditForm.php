<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui\Communication\Form;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\CompanyUserGui\Communication\CompanyUserGuiCommunicationFactory getFactory()
 */
class CompanyUserEditForm extends CompanyUserForm
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addCustomerSubForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            CompanyUserTransfer::CUSTOMER,
            CompanyUserCustomerUpdateForm::class,
            [
                'data_class' => CustomerTransfer::class,
                'label' => false,
                CompanyUserCustomerForm::OPTION_GENDER_CHOICES => $options[CompanyUserCustomerForm::OPTION_GENDER_CHOICES],
                CompanyUserCustomerForm::OPTION_SALUTATION_CHOICES => $options[CompanyUserCustomerForm::OPTION_SALUTATION_CHOICES],
                CompanyUserCustomerForm::OPTION_LOCALE_CHOICES => $options[CompanyUserCustomerForm::OPTION_LOCALE_CHOICES],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPluginForms(FormBuilderInterface $builder): AbstractType
    {
        foreach ($this->getFactory()->getCompanyUserEditFormPlugins() as $formPlugin) {
            $formPlugin->buildForm($builder);
        }

        return $this;
    }
}
