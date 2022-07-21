<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyGui\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\CompanyGui\Communication\CompanyGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CompanyGui\CompanyGuiConfig getConfig()
 */
class CompanyToCompanyUnitAddressForm extends AbstractType
{
    /**
     * @var string
     */
    protected const OPTION_COMPANY_CHOICES = 'company_choices';

    /**
     * @var string
     */
    protected const FIELD_FK_COMPANY = 'fkCompany';

    /**
     * @uses \Spryker\Zed\CompanyBusinessUnitGui\Communication\Controller\SuggestController::indexAction()
     *
     * @var string
     */
    protected const ROUTE_SUGGEST = '/company-gui/suggest';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(static::OPTION_COMPANY_CHOICES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addFkCompanyField($builder, $options);

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
            $this->handleCompanyBusinessUnitSearchPreSubmit($formEvent);
        });
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $formEvent
     *
     * @return void
     */
    protected function handleCompanyBusinessUnitSearchPreSubmit(FormEvent $formEvent): void
    {
        $data = $formEvent->getData();
        $form = $formEvent->getForm();

        if (!isset($data[static::FIELD_FK_COMPANY])) {
            return;
        }

        $companyChoices = $this->getFactory()
            ->createCompanyToCompanyUnitAddressFormDataProvider()
            ->getCompanyChoices($data[static::FIELD_FK_COMPANY]);

        $form->add(
            static::FIELD_FK_COMPANY,
            Select2ComboBoxType::class,
            $this->getCompanyFieldParameters($companyChoices),
        );
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addFkCompanyField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_FK_COMPANY,
            Select2ComboBoxType::class,
            $this->getCompanyFieldParameters($options[static::OPTION_COMPANY_CHOICES]),
        );

        return $this;
    }

    /**
     * @param array<string, int> $companyChoices
     *
     * @return array<string, mixed>
     */
    protected function getCompanyFieldParameters(array $companyChoices): array
    {
        return [
            'label' => 'Company',
            'placeholder' => 'Company',
            'choices' => $companyChoices,
            'required' => true,
            'constraints' => [
                new NotBlank(),
            ],
            'attr' => [
                'data-minimum-input-length' => 2,
                'data-autocomplete-url' => static::ROUTE_SUGGEST,
            ],
        ];
    }
}
