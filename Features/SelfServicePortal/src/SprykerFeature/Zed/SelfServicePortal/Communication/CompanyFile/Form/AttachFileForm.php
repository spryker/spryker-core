<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class AttachFileForm extends AbstractType
{
    /**
     * @var string
     */
    protected const BLOCK_PREFIX = 'fileAttachment';

    /**
     * @var string
     */
    public const OPTION_COMPANY_CHOICES = 'OPTION_COMPANY_CHOICES';

    /**
     * @var string
     */
    public const OPTION_COMPANY_USER_CHOICES = 'OPTION_COMPANY_USER_CHOICES';

    /**
     * @var string
     */
    public const OPTION_COMPANY_BUSINESS_UNIT_CHOICES = 'OPTION_COMPANY_BUSINESS_UNIT_CHOICES';

    /**
     * @var string
     */
    public const BUTTON_SAVE = 'save';

    /**
     * @var string
     */
    public const FIELD_COMPANY_IDS = 'companyIds';

    /**
     * @var string
     */
    public const FIELD_COMPANY_USER_IDS = 'companyUserIds';

    /**
     * @var string
     */
    public const FIELD_COMPANY_BUSINESS_UNIT_IDS = 'companyBusinessUnitIds';

    /**
     * @var string
     */
    protected const LABEL_BUTTON_SAVE = 'Save';

    /**
     * @var string
     */
    protected const LABEL_COMPANY = 'Company';

    /**
     * @var string
     */
    protected const LABEL_COMPANY_USER = 'Company User';

    /**
     * @var string
     */
    protected const LABEL_COMPANY_BUSINESS_UNIT = 'Company Business Unit';

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\FileAttachmentFormAutocompleteController::companyAction()
     *
     * @var string
     */
    protected const DATASOURCE_URL_PATH_COMPANY = '/self-service-portal/file-attachment-form-autocomplete/company';

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\FileAttachmentFormAutocompleteController::companyUserAction()
     *
     * @var string
     */
    protected const DATASOURCE_URL_PATH_COMPANY_USER = '/self-service-portal/file-attachment-form-autocomplete/company-user';

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\FileAttachmentFormAutocompleteController::companyBusinessUnitAction()
     *
     * @var string
     */
    protected const DATASOURCE_URL_PATH_COMPANY_BUSINESS_UNIT = '/self-service-portal/file-attachment-form-autocomplete/company-business-unit';

    /**
     * @var string
     */
    protected const PLACEHOLDER_SEARCH = 'Start typing to search...';

    /**
     * @var int
     */
    protected const DATASOURCE_MIN_CHARACTERS = 4;

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::BLOCK_PREFIX;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            static::OPTION_COMPANY_CHOICES,
            static::OPTION_COMPANY_USER_CHOICES,
            static::OPTION_COMPANY_BUSINESS_UNIT_CHOICES,
        ]);

        $resolver->setDefaults([
            static::OPTION_COMPANY_CHOICES => [],
            static::OPTION_COMPANY_USER_CHOICES => [],
            static::OPTION_COMPANY_BUSINESS_UNIT_CHOICES => [],
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
            ->addCompanyField($builder, $options)
            ->addCompanyUserField($builder, $options)
            ->addCompanyBusinessUnitField($builder, $options)
            ->addSubmitField($builder);

        $this->addPreSetDataEventListeners($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addPreSetDataEventListeners(FormBuilderInterface $builder): void
    {
        $this->addPreSetDataEventToCompanyField($builder);
        $this->addPreSetDataEventToCompanyUserField($builder);
        $this->addPreSetDataEventToCompanyBusinessUnitField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addPreSetDataEventToCompanyField(FormBuilderInterface $builder): void
    {
        $builder->get(static::FIELD_COMPANY_IDS)->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event): void {
                if (!$event->getData()) {
                    return;
                }

                $companyIds = (array)$event->getData();
                $companyTransfers = $this->getFactory()
                    ->createFileAttachFormDataProvider()
                    ->getCompanyCollectionByIds($companyIds);

                $choices = [];
                foreach ($companyTransfers as $companyTransfer) {
                    $choices[sprintf('%s (ID: %s)', $companyTransfer->getNameOrFail(), $companyTransfer->getIdCompanyOrFail())] = $companyTransfer->getIdCompanyOrFail();
                }

                /** @var \Symfony\Component\Form\FormInterface $form */
                $form = $event->getForm()->getParent();

                $this->replaceCompanyField($form, $choices);
            },
        );
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addPreSetDataEventToCompanyUserField(FormBuilderInterface $builder): void
    {
        $builder->get(static::FIELD_COMPANY_USER_IDS)->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event): void {
                if (!$event->getData()) {
                    return;
                }

                $companyUserIds = (array)$event->getData();
                $companyUserTransfers = $this->getFactory()
                    ->createFileAttachFormDataProvider()
                    ->getCompanyUserCollectionByIds($companyUserIds);

                $choices = [];
                foreach ($companyUserTransfers as $companyUserTransfer) {
                    $customerTransfer = $companyUserTransfer->getCustomerOrFail();
                    $choices[sprintf('%s %s', $customerTransfer->getFirstNameOrFail(), $customerTransfer->getLastNameOrFail())] = $companyUserTransfer->getIdCompanyUserOrFail();
                }

                /** @var \Symfony\Component\Form\FormInterface $form */
                $form = $event->getForm()->getParent();

                $this->replaceCompanyUserField($form, $choices);
            },
        );
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addPreSetDataEventToCompanyBusinessUnitField(FormBuilderInterface $builder): void
    {
        $builder->get(static::FIELD_COMPANY_BUSINESS_UNIT_IDS)->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event): void {
                if (!$event->getData()) {
                    return;
                }

                $companyBusinessUnitIds = (array)$event->getData();
                $companyBusinessUnitTransfers = $this->getFactory()
                    ->createFileAttachFormDataProvider()
                    ->getCompanyBusinessUnitCollectionByIds($companyBusinessUnitIds);

                $choices = [];
                foreach ($companyBusinessUnitTransfers as $companyBusinessUnitTransfer) {
                    $choices[sprintf('%s (ID: %s)', $companyBusinessUnitTransfer->getNameOrFail(), $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail())] = $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail();
                }

                /** @var \Symfony\Component\Form\FormInterface $form */
                $form = $event->getForm()->getParent();

                $this->replaceCompanyBusinessUnitField($form, $choices);
            },
        );
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array<string, int> $choices
     *
     * @return void
     */
    protected function replaceCompanyField(FormInterface $form, array $choices): void
    {
        $form->add(static::FIELD_COMPANY_IDS, Select2ComboBoxType::class, [
            'label' => static::LABEL_COMPANY,
            'multiple' => true,
            'required' => false,
            'choices' => $choices,
            'attr' => [
                'data-autocomplete-url' => static::DATASOURCE_URL_PATH_COMPANY,
                'data-minimum-input-length' => static::DATASOURCE_MIN_CHARACTERS,
                'data-placeholder' => static::PLACEHOLDER_SEARCH,
                'data-qa' => 'attach-company-field',
            ],
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array<string, int> $choices
     *
     * @return void
     */
    protected function replaceCompanyUserField(FormInterface $form, array $choices): void
    {
        $form->add(static::FIELD_COMPANY_USER_IDS, Select2ComboBoxType::class, [
            'label' => static::LABEL_COMPANY_USER,
            'multiple' => true,
            'required' => false,
            'choices' => $choices,
            'attr' => [
                'data-autocomplete-url' => static::DATASOURCE_URL_PATH_COMPANY_USER,
                'data-minimum-input-length' => static::DATASOURCE_MIN_CHARACTERS,
                'data-placeholder' => static::PLACEHOLDER_SEARCH,
                'data-qa' => 'attach-company-user-field',
            ],
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array<string, int> $choices
     *
     * @return void
     */
    protected function replaceCompanyBusinessUnitField(FormInterface $form, array $choices): void
    {
        $form->add(static::FIELD_COMPANY_BUSINESS_UNIT_IDS, Select2ComboBoxType::class, [
            'label' => static::LABEL_COMPANY_BUSINESS_UNIT,
            'multiple' => true,
            'required' => false,
            'choices' => $choices,
            'attr' => [
                'data-autocomplete-url' => static::DATASOURCE_URL_PATH_COMPANY_BUSINESS_UNIT,
                'data-minimum-input-length' => static::DATASOURCE_MIN_CHARACTERS,
                'data-placeholder' => static::PLACEHOLDER_SEARCH,
                'data-qa' => 'attach-company-business-unit-field',
            ],
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addCompanyField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_COMPANY_IDS, Select2ComboBoxType::class, [
            'label' => static::LABEL_COMPANY,
            'multiple' => true,
            'required' => false,
            'choices' => $options[static::OPTION_COMPANY_CHOICES],
            'attr' => [
                'data-autocomplete-url' => static::DATASOURCE_URL_PATH_COMPANY,
                'data-minimum-input-length' => static::DATASOURCE_MIN_CHARACTERS,
                'data-placeholder' => static::PLACEHOLDER_SEARCH,
                'data-qa' => 'attach-company-field',
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addCompanyUserField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_COMPANY_USER_IDS, Select2ComboBoxType::class, [
            'label' => static::LABEL_COMPANY_USER,
            'multiple' => true,
            'required' => false,
            'choices' => $options[static::OPTION_COMPANY_USER_CHOICES],
            'attr' => [
                'data-autocomplete-url' => static::DATASOURCE_URL_PATH_COMPANY_USER,
                'data-minimum-input-length' => static::DATASOURCE_MIN_CHARACTERS,
                'data-placeholder' => static::PLACEHOLDER_SEARCH,
                'data-qa' => 'attach-company-user-field',
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addCompanyBusinessUnitField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_COMPANY_BUSINESS_UNIT_IDS, Select2ComboBoxType::class, [
            'label' => static::LABEL_COMPANY_BUSINESS_UNIT,
            'multiple' => true,
            'required' => false,
            'choices' => $options[static::OPTION_COMPANY_BUSINESS_UNIT_CHOICES],
            'attr' => [
                'data-autocomplete-url' => static::DATASOURCE_URL_PATH_COMPANY_BUSINESS_UNIT,
                'data-minimum-input-length' => static::DATASOURCE_MIN_CHARACTERS,
                'data-placeholder' => static::PLACEHOLDER_SEARCH,
                'data-qa' => 'attach-company-business-unit-field',
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSubmitField(FormBuilderInterface $builder)
    {
        $builder->add(static::BUTTON_SAVE, SubmitType::class, [
            'label' => static::LABEL_BUTTON_SAVE,
            'attr' => [
                'data-qa' => 'attach-submit-button',
            ],
        ]);

        return $this;
    }
}
