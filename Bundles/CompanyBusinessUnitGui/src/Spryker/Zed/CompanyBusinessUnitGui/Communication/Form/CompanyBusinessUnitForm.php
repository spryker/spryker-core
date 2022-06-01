<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitGui\Communication\Form;

use Closure;
use Spryker\Zed\Gui\Communication\Form\Type\SelectType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitGui\Communication\CompanyBusinessUnitGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CompanyBusinessUnitGui\CompanyBusinessUnitGuiConfig getConfig()
 * @method \Spryker\Zed\CompanyBusinessUnitGui\Business\CompanyBusinessUnitGuiFacadeInterface getFacade()
 */
class CompanyBusinessUnitForm extends AbstractType
{
    /**
     * @var string
     */
    public const OPTION_PARENT_CHOICES_VALUES = 'parent_choices_values';

    /**
     * @var string
     */
    public const OPTION_PARENT_CHOICES_ATTRIBUTES = 'parent_choices_attributes';

    /**
     * @var string
     */
    public const FIELD_ID_COMPANY_BUSINESS_UNIT = 'id_company_business_unit';

    /**
     * @var string
     */
    public const FIELD_FK_COMPANY = 'fk_company';

    /**
     * @var string
     */
    public const FIELD_FK_PARENT_COMPANY_BUSINESS_UNIT = 'fk_parent_company_business_unit';

    /**
     * @var string
     */
    public const FIELD_NAME = 'name';

    /**
     * @var string
     */
    public const FIELD_IBAN = 'iban';

    /**
     * @var string
     */
    public const FIELD_BIC = 'bic';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'company-business-unit';
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
            ->addFkParentCompanyBusinessUnitField($builder)
            ->addNameField($builder)
            ->addIbanField($builder)
            ->addBicField($builder)
            ->addPluginForms($builder);

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            $this->getParentCompanyBusinessUnitFieldPreSubmitCallback(),
        );
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdCompanyBusinessUnitField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_COMPANY_BUSINESS_UNIT, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFkParentCompanyBusinessUnitField(
        FormBuilderInterface $builder
    ) {
        $builder->add(static::FIELD_FK_PARENT_COMPANY_BUSINESS_UNIT, SelectType::class, [
            'label' => 'Parent',
            'choices' => [],
            'placeholder' => 'No parent',
            'required' => false,
            'attr' => ['disabled' => 'disabled'],
        ]);

        return $this;
    }

    /**
     * @return \Closure
     */
    protected function getParentCompanyBusinessUnitFieldPreSubmitCallback(): Closure
    {
        return function (FormEvent $formEvent) {
            $data = $formEvent->getData();
            $form = $formEvent->getForm();
            $fkParentCompanyBusinessUnitFieldData = $data[static::FIELD_FK_PARENT_COMPANY_BUSINESS_UNIT];
            if (!$fkParentCompanyBusinessUnitFieldData) {
                return;
            }
            $form->add(static::FIELD_FK_PARENT_COMPANY_BUSINESS_UNIT, ChoiceType::class, [
                'label' => 'Parent',
                'choices' => [$fkParentCompanyBusinessUnitFieldData => $fkParentCompanyBusinessUnitFieldData],
                'placeholder' => 'No parent',
                'required' => false,
                'attr' => ['disabled' => 'disabled'],
            ]);
        };
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
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
    protected function addIbanField(FormBuilderInterface $builder)
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
    protected function addBicField(FormBuilderInterface $builder)
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
     *
     * @return $this
     */
    protected function addCompanyField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FK_COMPANY, ChoiceType::class, [
            'label' => 'Company',
            'placeholder' => 'Select one',
            'choice_loader' => new CallbackChoiceLoader(function () {
                return $this->getFactory()
                    ->createCompanyBusinessUnitFormDataProvider()
                    ->prepareCompanyChoices();
            }),
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
            new NotBlank(),
            new Length(['max' => 100]),
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPluginForms(FormBuilderInterface $builder)
    {
        foreach ($this->getFactory()->getCompanyBusinessUnitFormPlugins() as $formPlugin) {
            $formPlugin->buildForm($builder);
        }

        return $this;
    }
}
