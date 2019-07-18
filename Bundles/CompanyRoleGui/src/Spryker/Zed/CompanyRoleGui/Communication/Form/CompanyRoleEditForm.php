<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRoleGui\Communication\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class CompanyRoleEditForm extends CompanyRoleCreateForm
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addFkCompanyField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_FK_COMPANY, ChoiceType::class, [
            'choices' => $options[static::OPTION_COMPANY_CHOICES],
            'expanded' => false,
            'placeholder' => 'Select company',
            'label' => 'Company',
            'disabled' => 'disabled',
        ]);

        return $this;
    }
}
