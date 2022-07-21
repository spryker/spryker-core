<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRoleGui\Communication\Form;

use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\CompanyRoleGui\CompanyRoleGuiConfig getConfig()
 * @method \Spryker\Zed\CompanyRoleGui\Communication\CompanyRoleGuiCommunicationFactory getFactory()
 */
class CompanyRoleEditForm extends CompanyRoleCreateForm
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFkCompanyField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FK_COMPANY, ChoiceType::class, [
            'choice_loader' => new CallbackChoiceLoader(function () {
                return $this->getFactory()
                    ->createCompanyRoleCreateFormDataProvider()
                    ->prepareAvailableCompanies();
            }),
            'expanded' => false,
            'placeholder' => 'Select company',
            'label' => 'Company',
            'disabled' => 'disabled',
        ]);

        return $this;
    }
}
