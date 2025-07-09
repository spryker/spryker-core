<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Form;

use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyCriteriaFilterTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig getConfig()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 */
class SspAssetForm extends AbstractType
{
    /**
     * @var string
     */
    protected const FIELD_NAME = 'name';

    /**
     * @var string
     */
    public const FIELD_BUSINESS_UNIT_OWNER = 'companyBusinessUnit';

    /**
     * @var string
     */
    protected const FIELD_SERIAL_NUMBER = 'serialNumber';

    /**
     * @var string
     */
    protected const FIELD_STATUS = 'status';

    /**
     * @var string
     */
    protected const FIELD_NOTE = 'note';

    /**
     * @var string
     */
    public const FIELD_ASSIGNED_COMPANIES = 'assignedCompanies';

    /**
     * @var string
     */
    protected const LABEL_COMPANY = 'Company';

    /**
     * @var string
     */
    protected const LABEL_BUSINESS_UNIT_OWNER = 'Business unit owner';

    /**
     * @var string
     */
    public const FIELD_IMAGE = 'asset_image';

    /**
     * @var string
     */
    public const OPTION_ORIGINAL_IMAGE_URL = 'imageUrl';

    /**
     * @var string
     */
    public const FIELD_ASSIGNED_BUSINESS_UNITS = 'assignedBusinessUnits';

    /**
     * @var string
     */
    protected const LABEL_BUSINESS_UNIT = 'Business Units';

    /**
     * @uses \Spryker\Zed\CompanyGui\Communication\Controller\SuggestController::indexAction()
     *
     * @var string
     */
    protected const ROUTE_COMPANY_SUGGEST = '/company-gui/suggest';

    /**
     * @uses \Spryker\Zed\CompanyBusinessUnitGui\Communication\Controller\SuggestController::indexAction()
     *
     * @var string
     */
    protected const ROUTE_BUSINESS_UNIT_SUGGEST = '/company-business-unit-gui/suggest?';

    /**
     * @var string
     */
    protected const PLACEHOLDER_SEARCH = 'Start typing to search...';

    /**
     * @var string
     */
    public const OPTION_COMPANY_ASSIGMENT_OPTIONS = 'companyAssignments';

    /**
     * @var string
     */
    public const OPTION_BUSINESS_UNIT_ASSIGMENT_OPTIONS = 'businessUnitAssignments';

    /**
     * @var string
     */
    public const OPTION_BUSINESS_UNIT_OWNER = 'businessUnitOwner';

    /**
     * @var string
     */
    public const OPTION_STATUS_OPTIONS = 'statuses';

    /**
     * @var string
     */
    public const FORM_NAME = 'assetForm';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::FORM_NAME;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            static::OPTION_BUSINESS_UNIT_ASSIGMENT_OPTIONS,
            static::OPTION_BUSINESS_UNIT_OWNER,
            static::OPTION_STATUS_OPTIONS,
            static::OPTION_COMPANY_ASSIGMENT_OPTIONS,
            ]);

        $resolver->setDefaults([
            'data_class' => SspAssetTransfer::class,
            static::OPTION_ORIGINAL_IMAGE_URL => null,
            static::OPTION_BUSINESS_UNIT_ASSIGMENT_OPTIONS => [],
            static::OPTION_COMPANY_ASSIGMENT_OPTIONS => [],
            static::OPTION_STATUS_OPTIONS => [],
            static::OPTION_BUSINESS_UNIT_OWNER => [],
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
            ->addImageField($builder, $options)
            ->addNameField($builder)
            ->addSerialNumberField($builder)
            ->addStatusField($builder, $options)
            ->addNoteField($builder)
            ->addCompanyField($builder, $options)
            ->addAssignedBusinessUnitField($builder, $options)
            ->addBusinessUnitOwnerField($builder, $options);

        $this->addAssignedBusinessUnitDataListener($builder);
        $this->addBusinessUnitOwnerValidationListener($builder);
        $this->addCompanyAssignmentValidationListener($builder);
        $this->addBusinessUnitAssignmentValidationListener($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addBusinessUnitOwnerField(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add(static::FIELD_BUSINESS_UNIT_OWNER, Select2ComboBoxType::class, [
            'label' => static::LABEL_BUSINESS_UNIT_OWNER,
            'choices' => $options[static::OPTION_BUSINESS_UNIT_OWNER],
            'multiple' => false,
            'mapped' => true,
            'required' => false,
            'attr' => [
                'data-autocomplete-url' => static::ROUTE_BUSINESS_UNIT_SUGGEST,
                'data-clear-initial' => false,
                'dependent-autocomplete-key' => 'idsCompany',
                'data-depends-on-field' => '.js-select-dependable--assigned-companies',
                'class' => 'js-select-dependable js-select-dependable--business-unit spryker-form-select2combobox',
                'data-minimum-input-length' => 4,
                'placeholder' => static::PLACEHOLDER_SEARCH,
                'data-clearable' => true,
            ],
        ]);

        $builder->get(static::FIELD_BUSINESS_UNIT_OWNER)->addModelTransformer(
            new CallbackTransformer(
                function ($companyBusinessUnitTransfer) {
                    if (!$companyBusinessUnitTransfer instanceof CompanyBusinessUnitTransfer) {
                        return null;
                    }

                    return $companyBusinessUnitTransfer->getIdCompanyBusinessUnit();
                },
                function ($idCompanyBusinessUnitSubmitted) {
                    if (!$idCompanyBusinessUnitSubmitted) {
                        return null;
                    }

                    return (new CompanyBusinessUnitTransfer())->setIdCompanyBusinessUnit($idCompanyBusinessUnitSubmitted);
                },
            ),
        );

        return $this;
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
            'required' => true,
            'sanitize_xss' => true,
            'constraints' => [
                new NotBlank(),
                new Length([
                    'min' => 3,
                    'max' => 255,
                    'minMessage' => 'self_service_portal.asset.form.name.validation.min',
                ]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSerialNumberField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_SERIAL_NUMBER, TextType::class, [
            'label' => 'Serial number',
            'required' => false,
            'sanitize_xss' => true,
            'constraints' => [
                new Length([
                    'max' => 255,
                    'minMessage' => 'self_service_portal.asset.form.serial_number.validation.min',
                ]),
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
    protected function addStatusField(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add(static::FIELD_STATUS, ChoiceType::class, [
            'label' => 'Status',
            'required' => true,
            'choices' => $options[static::OPTION_STATUS_OPTIONS],
            'multiple' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNoteField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_NOTE, TextareaType::class, [
            'label' => 'Note',
            'required' => false,
            'sanitize_xss' => true,
            'constraints' => [
                new Length([
                    'max' => 1000,
                    'minMessage' => 'self_service_portal.asset.form.note.validation.min',
                ]),
            ],
            'attr' => ['rows' => 5],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addImageField(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add(static::FIELD_IMAGE, SspAssetImageForm::class, [
            'mapped' => false,
            SspAssetImageForm::OPTION_ORIGINAL_IMAGE_URL => $options[static::OPTION_ORIGINAL_IMAGE_URL] ?? null,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addCompanyField(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add(static::FIELD_ASSIGNED_COMPANIES, Select2ComboBoxType::class, [
            'label' => static::LABEL_COMPANY,
            'choices' => $options[static::OPTION_COMPANY_ASSIGMENT_OPTIONS],
            'multiple' => true,
            'mapped' => false,
            'required' => false,
            'attr' => [
                'data-autocomplete-url' => static::ROUTE_COMPANY_SUGGEST,
                'data-minimum-input-length' => 4,
                'dependent-autocomplete-key' => 'idsCompany',
                'data-dependent-name' => 'idsCompany',
                'placeholder' => static::PLACEHOLDER_SEARCH,
                'data-qa' => 'ssp-asset-assigned-companies-field',
                'class' => 'js-select-dependable--assigned-companies',
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
    protected function addAssignedBusinessUnitField(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add(static::FIELD_ASSIGNED_BUSINESS_UNITS, Select2ComboBoxType::class, [
            'label' => static::LABEL_BUSINESS_UNIT,
            'choices' => $options[static::OPTION_BUSINESS_UNIT_ASSIGMENT_OPTIONS],
            'multiple' => true,
            'required' => false,
            'mapped' => false,
            'attr' => [
                'data-autocomplete-url' => static::ROUTE_BUSINESS_UNIT_SUGGEST,
                'data-clear-initial' => false,
                'dependent-autocomplete-key' => 'idsCompany',
                'data-depends-on-field' => '.js-select-dependable--assigned-companies',
                'class' => 'js-select-dependable js-select-dependable--business-unit spryker-form-select2combobox',
                'data-qa' => 'ssp-asset-business-unit-field',
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addAssignedBusinessUnitDataListener(FormBuilderInterface $builder): void
    {
        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event): void {
                $sspAssetTransfer = $event->getData();

                if (!$sspAssetTransfer instanceof SspAssetTransfer) {
                    return;
                }

                if ($event->getForm()->has(static::FIELD_ASSIGNED_BUSINESS_UNITS)) {
                    $event->getForm()->get(static::FIELD_ASSIGNED_BUSINESS_UNITS)->setData($this->extractBusinessUnitsFromTransfer($sspAssetTransfer));
                }
                if ($event->getForm()->has(static::FIELD_ASSIGNED_COMPANIES)) {
                    $event->getForm()->get(static::FIELD_ASSIGNED_COMPANIES)->setData($this->extractCompaniesFromTransfer($sspAssetTransfer));
                }
            },
        );
    }

    /**
     * Adds a validation listener to ensure business unit owner is one of the assigned business units
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addBusinessUnitOwnerValidationListener(FormBuilderInterface $builder): void
    {
        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event): void {
                $data = $event->getData();
                $form = $event->getForm();

                $businessUnitOwnerId = $data[static::FIELD_BUSINESS_UNIT_OWNER] ?? null;
                $assignedBusinessUnitIds = $data[static::FIELD_ASSIGNED_BUSINESS_UNITS] ?? [];

                if (!$businessUnitOwnerId && $assignedBusinessUnitIds === []) {
                    return;
                }

                if (!$businessUnitOwnerId && $assignedBusinessUnitIds !== []) {
                    $form->addError(new FormError('Business unit owner is required when business units are assigned.'));

                    return;
                }

                if (!in_array($businessUnitOwnerId, $assignedBusinessUnitIds)) {
                    $form->addError(new FormError('Business unit owner must be one of the assigned business units.'));
                }
            },
        );
    }

    /**
     * Adds a validation listener to ensure assigned companies contain all companies from assigned business units
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addCompanyAssignmentValidationListener(FormBuilderInterface $builder): void
    {
        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event): void {
                $data = $event->getData();
                $form = $event->getForm();

                if (!isset($data[static::FIELD_ASSIGNED_BUSINESS_UNITS]) || !$data[static::FIELD_ASSIGNED_BUSINESS_UNITS]) {
                    return;
                }

                if (!isset($data[static::FIELD_ASSIGNED_COMPANIES]) || !$data[static::FIELD_ASSIGNED_COMPANIES]) {
                    $form->addError(new FormError('Companies must be assigned when business units are assigned.'));

                    return;
                }

                $assignedBusinessUnitIds = array_map('intval', $data[static::FIELD_ASSIGNED_BUSINESS_UNITS]);
                $assignedCompanyIds = array_map('intval', $data[static::FIELD_ASSIGNED_COMPANIES]);

                $businessUnitOptions = $form->getConfig()->getOption(static::OPTION_BUSINESS_UNIT_ASSIGMENT_OPTIONS);
                $companyOptions = $form->getConfig()->getOption(static::OPTION_COMPANY_ASSIGMENT_OPTIONS);

                if (!$businessUnitOptions || !$companyOptions) {
                    return;
                }

                $companyBusinessUnitFacade = $this->getFactory()->getCompanyBusinessUnitFacade();
                $businessUnitCollection = $companyBusinessUnitFacade->getCompanyBusinessUnitCollection(
                    (new CompanyBusinessUnitCriteriaFilterTransfer())->setCompanyBusinessUnitIds($assignedBusinessUnitIds),
                );

                $requiredCompanyIds = [];
                foreach ($businessUnitCollection->getCompanyBusinessUnits() as $businessUnit) {
                    if ($businessUnit->getCompany() && $businessUnit->getCompany()->getIdCompany()) {
                        $requiredCompanyIds[] = $businessUnit->getCompany()->getIdCompany();
                    }
                }

                $requiredCompanyIds = array_unique($requiredCompanyIds);
                $missingCompanyIds = array_diff($requiredCompanyIds, $assignedCompanyIds);

                if ($missingCompanyIds) {
                    $companyFacade = $this->getFactory()->getCompanyFacade();
                    $missingCompanyCollection = $companyFacade->getCompanyCollection(
                        (new CompanyCriteriaFilterTransfer())->setCompanyIds($missingCompanyIds),
                    );

                    $missingCompanyNames = [];
                    foreach ($missingCompanyCollection->getCompanies() as $company) {
                        $missingCompanyNames[] = $company->getName();
                    }

                    $errorMessage = sprintf(
                        'All companies that own the assigned business units must be included in assigned companies. Missing companies: %s.',
                        implode(', ', $missingCompanyNames),
                    );

                    $form->addError(new FormError($errorMessage));
                }
            },
        );
    }

    /**
     * Adds a validation listener to ensure that if a company is selected, at least one business unit of that company is selected too
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addBusinessUnitAssignmentValidationListener(FormBuilderInterface $builder): void
    {
        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event): void {
                $data = $event->getData();
                $form = $event->getForm();

                if (!isset($data[static::FIELD_ASSIGNED_COMPANIES]) || !$data[static::FIELD_ASSIGNED_COMPANIES]) {
                    return;
                }

                if (!isset($data[static::FIELD_ASSIGNED_BUSINESS_UNITS]) || !$data[static::FIELD_ASSIGNED_BUSINESS_UNITS]) {
                    return;
                }

                $assignedCompanyIds = array_map('intval', $data[static::FIELD_ASSIGNED_COMPANIES]);
                $assignedBusinessUnitIds = array_map('intval', $data[static::FIELD_ASSIGNED_BUSINESS_UNITS]);

                $companyBusinessUnitFacade = $this->getFactory()->getCompanyBusinessUnitFacade();
                $businessUnitCollection = $companyBusinessUnitFacade->getCompanyBusinessUnitCollection(
                    (new CompanyBusinessUnitCriteriaFilterTransfer())->setCompanyIds($assignedCompanyIds),
                );

                $companyBusinessUnits = [];
                foreach ($businessUnitCollection->getCompanyBusinessUnits() as $businessUnit) {
                    if ($businessUnit->getFkCompany() && $businessUnit->getIdCompanyBusinessUnit()) {
                        $companyId = $businessUnit->getFkCompany();
                        $businessUnitId = $businessUnit->getIdCompanyBusinessUnit();

                        if (!isset($companyBusinessUnits[$companyId])) {
                            $companyBusinessUnits[$companyId] = [];
                        }

                        $companyBusinessUnits[$companyId][] = $businessUnitId;
                    }
                }

                $companyFacade = $this->getFactory()->getCompanyFacade();
                $companiesWithoutBusinessUnits = [];

                foreach ($assignedCompanyIds as $companyId) {
                    $hasSelectedBusinessUnit = false;

                    if (isset($companyBusinessUnits[$companyId])) {
                        foreach ($companyBusinessUnits[$companyId] as $businessUnitId) {
                            if (in_array($businessUnitId, $assignedBusinessUnitIds)) {
                                $hasSelectedBusinessUnit = true;

                                break;
                            }
                        }
                    }

                    if (!$hasSelectedBusinessUnit) {
                        $companyCollection = $companyFacade->getCompanyCollection(
                            (new CompanyCriteriaFilterTransfer())->setCompanyIds([$companyId]),
                        );

                        if ($companyCollection->getCompanies()->count() > 0) {
                            $company = $companyCollection->getCompanies()->getIterator()->current();
                            $companiesWithoutBusinessUnits[] = $company->getNameOrFail();
                        }
                    }
                }

                if ((bool)$companiesWithoutBusinessUnits) {
                    $errorMessage = sprintf(
                        'For each selected company, at least one of its business units must be selected. Missing business units for companies: %s.',
                        implode(', ', $companiesWithoutBusinessUnits),
                    );

                    $form->addError(new FormError($errorMessage));
                }
            },
        );
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     *
     * @return array<int>
     */
    protected function extractBusinessUnitsFromTransfer(SspAssetTransfer $sspAssetTransfer): array
    {
        $sspAssetAssignmentTransfers = $sspAssetTransfer->getBusinessUnitAssignments();
        if ($sspAssetAssignmentTransfers->count() === 0) {
            return [];
        }

        $businessUnitIds = [];
        foreach ($sspAssetAssignmentTransfers as $sspAssetAssignmentTransfer) {
            $companyBusinessUnitTransfer = $sspAssetAssignmentTransfer->getCompanyBusinessUnit();
            if ($companyBusinessUnitTransfer) {
                $businessUnitIds[sprintf(
                    '%s (ID: %s)',
                    $companyBusinessUnitTransfer->getNameOrFail(),
                    $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail(),
                )] = $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail();
            }
        }

        return $businessUnitIds;
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     *
     * @return array<int>
     */
    protected function extractCompaniesFromTransfer(SspAssetTransfer $sspAssetTransfer): array
    {
        $sspAssetAssignmentTransfers = $sspAssetTransfer->getBusinessUnitAssignments();
        if ($sspAssetAssignmentTransfers->count() === 0) {
            return [];
        }

        $companyIds = [];
        foreach ($sspAssetAssignmentTransfers as $sspAssetAssignmentTransfer) {
            $companyBusinessUnitTransfer = $sspAssetAssignmentTransfer->getCompanyBusinessUnit();
            if ($companyBusinessUnitTransfer) {
                $companyTransfer = $companyBusinessUnitTransfer->getCompanyOrFail();
                $companyIds[sprintf(
                    '%s (ID: %s)',
                    $companyTransfer->getNameOrFail(),
                    $companyTransfer->getIdCompanyOrFail(),
                )] = $companyTransfer->getIdCompanyOrFail();
            }
        }

        return $companyIds;
    }
}
