<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySupplierGui\Communication\Form;

use Generated\Shared\Transfer\CompanyTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CompanyTypeChoiceFormType extends AbstractType
{
    public const OPTION_VALUES_COMPANY_TYPE_CHOICES = 'company_type_value_options';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    public function buildForm(FormBuilderInterface $builder, array $options): self
    {
        $this->addCompanyTypeField($builder, $options);

        return $this;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setRequired(static::OPTION_VALUES_COMPANY_TYPE_CHOICES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    protected function addCompanyTypeField(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(CompanyTransfer::FK_COMPANY_TYPE, ChoiceType::class, [
            'label' => 'Company type',
            'placeholder' => 'Select one',
            'required' => true,
            'choices' => $options[static::OPTION_VALUES_COMPANY_TYPE_CHOICES],
            'constraints' => [
                new NotBlank(),
            ],
        ]);
    }
}
