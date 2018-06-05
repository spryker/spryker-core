<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitGui\Communication\CompanyBusinessUnitGuiCommunicationFactory getFactory()
 */
class CompanyBusinessUnitForm extends AbstractType
{
    public const OPTION_COMPANY_CHOICES = 'company_choices';
    public const OPTION_PARENT_CHOICES_VALUES = 'parent_choices_values';
    public const OPTION_PARENT_CHOICES_ATTRIBUTES = 'parent_choices_attributes';

    protected const FIELD_ID_COMPANY_BUSINESS_UNIT = 'id_company_business_unit';
    protected const FIELD_FK_COMPANY = 'fk_company';
    protected const FIELD_FK_PARENT_COMPANY_BUSINESS_UNIT = 'fk_parent_company_business_unit';
    protected const FIELD_NAME = 'name';
    protected const FIELD_IBAN = 'iban';
    protected const FIELD_BIC = 'bic';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'company-business-unit';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(static::OPTION_COMPANY_CHOICES);
        $resolver->setRequired(static::OPTION_PARENT_CHOICES_VALUES);
        $resolver->setRequired(static::OPTION_PARENT_CHOICES_ATTRIBUTES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addCompanyField($builder, $options[static::OPTION_COMPANY_CHOICES])
            ->addIdCompanyBusinessUnitField($builder)
            ->addParentNameField(
                $builder,
                $options[static::OPTION_PARENT_CHOICES_VALUES],
                $options[static::OPTION_PARENT_CHOICES_ATTRIBUTES]
            )
            ->addNameField($builder)
            ->addIbanField($builder)
            ->addBicField($builder)
            ->addPluginForms($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdCompanyBusinessUnitField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_ID_COMPANY_BUSINESS_UNIT, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string[] $choicesValues [unitKey => idUnit]
     * @param array[] $choicesAttributes [unitKey => ['data-id_company' => idCompany]
     *
     * @return \Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\CompanyBusinessUnitForm
     */
    protected function addParentNameField(
        FormBuilderInterface $builder,
        array $choicesValues,
        array $choicesAttributes
    ): CompanyBusinessUnitForm {
        $builder->add(static::FIELD_FK_PARENT_COMPANY_BUSINESS_UNIT, ChoiceType::class, [
            'label' => 'Parent',
            'placeholder' => 'No parent',
            'choices' => $choicesValues,
            'choices_as_values' => true,
            'required' => false,
            'choice_attr' => $choicesAttributes,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_NAME, TextType::class, [
            'label' => 'Name',
            'constraints' => $this->getTextFieldConstraints(),
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIbanField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_IBAN, TextType::class, [
            'label' => 'IBAN',
            'required' => false,
            'constraints' => [
                new Length(['max' => 100]),
            ],
            'empty_data' => '',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addBicField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_BIC, TextType::class, [
            'label' => 'BIC',
            'required' => false,
            'constraints' => [
                new Length(['max' => 100]),
            ],
            'empty_data' => '',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addCompanyField(FormBuilderInterface $builder, array $choices): self
    {
        $builder->add(static::FIELD_FK_COMPANY, ChoiceType::class, [
            'label' => 'Company',
            'placeholder' => 'Select one',
            'choices' => $choices,
            'choices_as_values' => true,
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    /**
     * @return array
     */
    protected function getTextFieldConstraints(): array
    {
        return [
            new Required(),
            new NotBlank(),
            new Length(['max' => 100]),
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPluginForms(FormBuilderInterface $builder): AbstractType
    {
        foreach ($this->getFactory()->getCompanyBusinessUnitFormPlugins() as $formPlugin) {
            $formPlugin->buildForm($builder);
        }

        return $this;
    }
}
