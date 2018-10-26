<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyUserBusinessUnitForm extends AbstractType
{
    public const OPTION_VALUES_BUSINESS_UNITS_CHOICES = 'company_business_unit_choices';
    public const OPTION_ATTRIBUTES_BUSINESS_UNITS_CHOICES = 'company_business_unit_attributes';

    public const FIELD_FK_COMPANY_BUSINESS_UNIT = 'fk_company_business_unit';

    protected const TEMPLATE_PATH = '@CompanyBusinessUnitGui/CompanyUser/company_business_unit.twig';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addCompanyBusinessUnitCollectionField($builder, $options);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(static::OPTION_VALUES_BUSINESS_UNITS_CHOICES);
        $resolver->setRequired(static::OPTION_ATTRIBUTES_BUSINESS_UNITS_CHOICES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addCompanyBusinessUnitCollectionField(FormBuilderInterface $builder, array $options): self
    {
        $builder->add(static::FIELD_FK_COMPANY_BUSINESS_UNIT, ChoiceType::class, [
            'label' => 'Business Unit',
            'placeholder' => 'Business Unit name',
            'choices' => $options[static::OPTION_VALUES_BUSINESS_UNITS_CHOICES],
            'choice_attr' => $options[static::OPTION_ATTRIBUTES_BUSINESS_UNITS_CHOICES],
            'choices_as_values' => true,
            'required' => true,
            'attr' => [
                'template_path' => $this->getTemplatePath(),
            ],
        ]);

        return $this;
    }

    /**
     * @return string
     */
    protected function getTemplatePath(): string
    {
        return static::TEMPLATE_PATH;
    }
}
