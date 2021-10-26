<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitGui\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\SelectType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitGui\CompanyBusinessUnitGuiConfig getConfig()
 * @method \Spryker\Zed\CompanyBusinessUnitGui\Business\CompanyBusinessUnitGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\CompanyBusinessUnitGui\Communication\CompanyBusinessUnitGuiCommunicationFactory getFactory()
 */
class CompanyUserBusinessUnitForm extends AbstractType
{
    /**
     * @var string
     */
    public const OPTION_VALUES_BUSINESS_UNITS_CHOICES = 'company_business_unit_choices';

    /**
     * @var string
     */
    public const OPTION_ATTRIBUTES_BUSINESS_UNITS_CHOICES = 'company_business_unit_attributes';

    /**
     * @var string
     */
    public const FIELD_FK_COMPANY_BUSINESS_UNIT = 'fk_company_business_unit';

    /**
     * @uses \Spryker\Zed\CompanyBusinessUnitGui\Communication\Controller\SuggestController::indexAction()
     *
     * @var string
     */
    protected const ROUTE_SUGGEST = '/company-business-unit-gui/suggest';

    /**
     * @var string
     */
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

        $this->addPreSubmitEventListener($builder);
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
     * @param \Symfony\Component\Form\FormEvent $formEvent
     *
     * @return void
     */
    public function companyBusinessUnitSearchPreSubmitHandler(FormEvent $formEvent): void
    {
        $data = $formEvent->getData();
        $form = $formEvent->getForm();

        if (!isset($data[static::FIELD_FK_COMPANY_BUSINESS_UNIT])) {
            return;
        }

        $companyBusinessUnitChoices = $this->getFactory()
            ->createCompanyUserBusinessUnitFormDataProvider()
            ->getOptions($data[static::FIELD_FK_COMPANY_BUSINESS_UNIT]);

        $form->add(
            static::FIELD_FK_COMPANY_BUSINESS_UNIT,
            SelectType::class,
            $this->getCompanyBusinessUnitFieldParameters($companyBusinessUnitChoices),
        );
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addPreSubmitEventListener(FormBuilderInterface $builder): void
    {
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $formEvent): void {
            $this->companyBusinessUnitSearchPreSubmitHandler($formEvent);
        });
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addCompanyBusinessUnitCollectionField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_FK_COMPANY_BUSINESS_UNIT,
            SelectType::class,
            $this->getCompanyBusinessUnitFieldParameters($options),
        );

        return $this;
    }

    /**
     * @param array $companyBusinessUnitChoices
     *
     * @return array
     */
    protected function getCompanyBusinessUnitFieldParameters(array $companyBusinessUnitChoices = []): array
    {
        return [
            'label' => 'Business Unit',
            'placeholder' => 'Business Unit name',
            'choices' => $companyBusinessUnitChoices,
            'required' => true,
            'constraints' => [
                new NotBlank(),
            ],
            'attr' => [
                'template_path' => $this->getTemplatePath(),
            ],
            'url' => static::ROUTE_SUGGEST,
        ];
    }

    /**
     * @return string
     */
    protected function getTemplatePath(): string
    {
        return static::TEMPLATE_PATH;
    }
}
