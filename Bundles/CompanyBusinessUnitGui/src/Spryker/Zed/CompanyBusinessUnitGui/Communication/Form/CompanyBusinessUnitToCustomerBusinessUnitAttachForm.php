<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitGui\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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
class CompanyBusinessUnitToCustomerBusinessUnitAttachForm extends AbstractType
{
    /**
     * @var string
     */
    protected const OPTION_COMPANY_BUSINESS_UNIT_CHOICES = 'company_business_unit_choices';

    /**
     * @var string
     */
    protected const FIELD_FK_COMPANY_BUSINESS_UNIT = 'fk_company_business_unit';

    /**
     * @var string
     */
    protected const FIELD_FK_COMPANY = 'fk_company';

    /**
     * @uses \Spryker\Zed\CompanyBusinessUnitGui\Communication\Controller\SuggestController::indexAction()
     *
     * @var string
     */
    protected const ROUTE_SUGGEST = '/company-business-unit-gui/suggest';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(static::OPTION_COMPANY_BUSINESS_UNIT_CHOICES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addFkCompanyBusinessUnitField($builder, $options)
            ->addFkCompanyField($builder);

        $this->addPreSubmitEventListener($builder);
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
     * @param \Symfony\Component\Form\FormEvent $formEvent
     *
     * @return void
     */
    protected function companyBusinessUnitSearchPreSubmitHandler(FormEvent $formEvent): void
    {
        $data = $formEvent->getData();
        $form = $formEvent->getForm();

        if (!isset($data[static::FIELD_FK_COMPANY_BUSINESS_UNIT])) {
            return;
        }

        $companyBusinessUnitChoices = $this->getFactory()
            ->createCompanyBusinessUnitToCustomerBusinessUnitAttachFormDataProvider()
            ->getCompanyBusinessUnitChoices($data[static::FIELD_FK_COMPANY_BUSINESS_UNIT]);

        $form->add(
            static::FIELD_FK_COMPANY_BUSINESS_UNIT,
            Select2ComboBoxType::class,
            $this->getCompanyBusinessUnitFieldParameters($companyBusinessUnitChoices),
        );
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addFkCompanyBusinessUnitField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_FK_COMPANY_BUSINESS_UNIT,
            Select2ComboBoxType::class,
            $this->getCompanyBusinessUnitFieldParameters($options[static::OPTION_COMPANY_BUSINESS_UNIT_CHOICES]),
        );

        return $this;
    }

    /**
     * @param array<string, int> $companyBusinessUnitChoices
     *
     * @return array<string, mixed>
     */
    protected function getCompanyBusinessUnitFieldParameters(array $companyBusinessUnitChoices): array
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
                'data-depends-on-field' => '#customer_business_unit_attach_fk_company',
                'data-dependent-autocomplete-key' => 'idCompany',
                'data-minimum-input-length' => 2,
                'data-autocomplete-url' => static::ROUTE_SUGGEST,
            ],
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFkCompanyField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FK_COMPANY, HiddenType::class);

        return $this;
    }
}
