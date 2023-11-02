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

/**
 * @method \Spryker\Zed\CompanyGui\Communication\CompanyGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CompanyGui\CompanyGuiConfig getConfig()
 */
class CompanyToCompanyRoleCreateForm extends AbstractType
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
     * @uses \Spryker\Zed\CompanyGui\Communication\Controller\SuggestController::indexAction()
     *
     * @var string
     */
    protected const ROUTE_SUGGEST = '/company-gui/suggest';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
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

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $formEvent): void {
            $this->companySearchPreSubmitHandler($formEvent);
        });
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $formEvent
     *
     * @return void
     */
    protected function companySearchPreSubmitHandler(FormEvent $formEvent): void
    {
        $data = $formEvent->getData();
        $form = $formEvent->getForm();

        if (!isset($data[static::FIELD_FK_COMPANY])) {
            return;
        }

        $companyChoices = $this->getFactory()
            ->createCompanyToCompanyRoleCreateFormDataProvider()
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
            $this->getCompanyFieldParameters($options[static::OPTION_COMPANY_CHOICES], (bool)$builder->getData()->getidCompanyRole()),
        );

        return $this;
    }

    /**
     * @param array<string, int> $companyChoices
     * @param bool $disabled
     *
     * @return array<string, mixed>
     */
    protected function getCompanyFieldParameters(array $companyChoices, bool $disabled = false): array
    {
        $companyFieldParameters = [
            'label' => 'Company',
            'placeholder' => 'Select company',
            'choices' => $companyChoices,
            'required' => true,
            'attr' => [
                'data-minimum-input-length' => 2,
                'data-autocomplete-url' => static::ROUTE_SUGGEST,
            ],
        ];

        if (!$disabled) {
            return $companyFieldParameters;
        }

        return array_merge($companyFieldParameters, ['disabled' => 'disabled']);
    }
}
