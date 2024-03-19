<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitGui\Communication\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitGui\Communication\CompanyBusinessUnitGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CompanyBusinessUnitGui\CompanyBusinessUnitGuiConfig getConfig()
 * @method \Spryker\Zed\CompanyBusinessUnitGui\Business\CompanyBusinessUnitGuiFacadeInterface getFacade()
 */
class CompanyBusinessUnitEditForm extends CompanyBusinessUnitForm
{
    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            static::OPTION_PARENT_CHOICES_VALUES,
            static::OPTION_PARENT_CHOICES_ATTRIBUTES,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addCompanyField($builder)
            ->addIdCompanyBusinessUnitField($builder)
            ->addFkParentCompanyBusinessUnitFieldForEdit(
                $builder,
                $options[static::OPTION_PARENT_CHOICES_VALUES],
                $options[static::OPTION_PARENT_CHOICES_ATTRIBUTES],
            )
            ->addNameField($builder)
            ->addIbanField($builder)
            ->addBicField($builder)
            ->addPhoneField($builder)
            ->addPluginForms($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCompanyField(FormBuilderInterface $builder)
    {
        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, int> $choiceValues
     * @param array<string, array<string, int>> $choiceAttributes
     *
     * @return $this
     */
    protected function addFkParentCompanyBusinessUnitFieldForEdit(
        FormBuilderInterface $builder,
        array $choiceValues,
        array $choiceAttributes
    ) {
        $builder->add(static::FIELD_FK_PARENT_COMPANY_BUSINESS_UNIT, ChoiceType::class, [
            'label' => 'Parent',
            'placeholder' => 'No parent',
            'choices' => $choiceValues,
            'required' => false,
            'choice_attr' => $choiceAttributes,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPluginForms(FormBuilderInterface $builder)
    {
        foreach ($this->getFactory()->getCompanyBusinessUnitEditFormPlugins() as $formPlugin) {
            $formPlugin->buildForm($builder);
        }

        return $this;
    }
}
