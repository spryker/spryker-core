<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipGui\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class MerchantRelationshipEditForm extends MerchantRelationshipCreateForm
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addMerchantDisabledField($builder, $options[static::OPTION_MERCHANT_CHOICES])
            ->addCompanyDisabledField($builder, $options)
            ->addOwnerCompanyBusinessUnitField($builder, $options)
            ->addAssignedCompanyBusinessUnitField($builder, $options[static::OPTION_ASSIGNED_COMPANY_BUSINESS_UNIT_CHOICES]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addCompanyDisabledField(FormBuilderInterface $builder, array $options): self
    {
        $builder->add(static::FIELD_FK_COMPANY, Select2ComboBoxType::class, [
            'label' => static::COMPANY_FIELD_LABEL,
            'placeholder' => static::COMPANY_FIELD_PLACEHOLDER,
            'choices' => array_flip($options[static::OPTION_COMPANY_CHOICES]),
            'mapped' => false,
            'data' => $options[static::OPTION_SELECTED_COMPANY],
            'choices_as_values' => true,
            'disabled' => 'disabled',
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addMerchantDisabledField(FormBuilderInterface $builder, array $choices): self
    {
        $builder->add(static::FIELD_FK_MERCHANT, Select2ComboBoxType::class, [
            'label' => static::MERCHANT_FIELD_LABEL,
            'placeholder' => static::MERCHANT_FIELD_PLACEHOLDER,
            'choices' => array_flip($choices),
            'choices_as_values' => true,
            'disabled' => 'disabled',
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }
}
