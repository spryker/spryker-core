<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\Form;

use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Length;

/**
 * @method \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\MerchantRelationRequestMerchantPortalGuiConfig getConfig()
 * @method \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\MerchantRelationRequestMerchantPortalGuiCommunicationFactory getFactory()
 */
class MerchantRelationRequestForm extends AbstractType
{
    /**
     * @var string
     */
    public const OPTION_ASSIGNEE_BUSINESS_UNITS_CHOICES = 'OPTION_ASSIGNEE_BUSINESS_UNITS_CHOICES';

    /**
     * @var string
     */
    public const FIELD_APPROVE = 'approve';

    /**
     * @var string
     */
    public const FIELD_REJECT = 'reject';

    /**
     * @var string
     */
    public const LABEL_APPROVE = 'Approve';

    /**
     * @var string
     */
    public const LABEL_REJECT = 'Reject';

    /**
     * @var string
     */
    protected const LABEL_REQUEST_NOTE = 'Message from the Company';

    /**
     * @var string
     */
    protected const LABEL_DECISION_NOTE = 'Message to the company';

    /**
     * @var string
     */
    protected const LABEL_IS_SPLIT_ENABLED = 'Create a separate merchant relation per each business unit';

    /**
     * @var string
     */
    protected const LABEL_ASSIGNEE_COMPANY_BUSINESS_UNITS = 'Business Units';

    /**
     * @var int
     */
    protected const FIELD_ASSIGNEE_COMPANY_BUSINESS_UNITS_MIN_COUNT = 1;

    /**
     * @var string
     */
    protected const FIELD_ASSIGNEE_COMPANY_BUSINESS_UNITS_MIN_MESSAGE = 'At least one business unit must be included in the merchant relation.';

    /**
     * @var int
     */
    protected const FIELD_DECISION_NOTE_MAX_LENGTH = 5000;

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return '';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MerchantRelationRequestTransfer::class,
        ]);

        $resolver->setRequired(static::OPTION_ASSIGNEE_BUSINESS_UNITS_CHOICES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @param array<mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addApproveButton($builder)
            ->addRejectButton($builder)
            ->addOwnerCompanyBusinessUnitSubform($builder)
            ->addCompanyUserSubform($builder)
            ->addRequestNoteField($builder)
            ->addDecisionNoteField($builder)
            ->addIsSplitEnabledField($builder)
            ->addAssigneeCompanyBusinessUnitsField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return $this
     */
    protected function addApproveButton(FormBuilderInterface $builder)
    {
        if ($this->isEditableMerchantRelationRequest($builder)) {
            $builder->add(static::FIELD_APPROVE, SubmitType::class, [
                'label' => static::LABEL_APPROVE,
            ]);
        }

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return $this
     */
    protected function addRejectButton(FormBuilderInterface $builder)
    {
        if ($this->isEditableMerchantRelationRequest($builder)) {
            $builder->add(static::FIELD_REJECT, SubmitType::class, [
                'label' => static::LABEL_REJECT,
            ]);
        }

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return $this
     */
    protected function addRequestNoteField(FormBuilderInterface $builder)
    {
        $builder->add(MerchantRelationRequestTransfer::REQUEST_NOTE, TextareaType::class, [
            'label' => static::LABEL_REQUEST_NOTE,
            'required' => false,
            'disabled' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return $this
     */
    protected function addDecisionNoteField(FormBuilderInterface $builder)
    {
        $builder->add(MerchantRelationRequestTransfer::DECISION_NOTE, TextareaType::class, [
            'label' => static::LABEL_DECISION_NOTE,
            'sanitize_xss' => true,
            'required' => false,
            'disabled' => !$this->isEditableMerchantRelationRequest($builder),
            'constraints' => [
                new Length(['max' => static::FIELD_DECISION_NOTE_MAX_LENGTH]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return $this
     */
    protected function addIsSplitEnabledField(FormBuilderInterface $builder)
    {
        if ($this->isEditableMerchantRelationRequest($builder) && count($builder->getData()->getAssigneeCompanyBusinessUnits()) > 1) {
            $builder->add(MerchantRelationRequestTransfer::IS_SPLIT_ENABLED, CheckboxType::class, [
                'label' => static::LABEL_IS_SPLIT_ENABLED,
                'required' => false,
            ]);
        }

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @param array<mixed> $options
     *
     * @return $this
     */
    protected function addAssigneeCompanyBusinessUnitsField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(MerchantRelationRequestTransfer::ASSIGNEE_COMPANY_BUSINESS_UNITS, ChoiceType::class, [
            'choices' => $options[static::OPTION_ASSIGNEE_BUSINESS_UNITS_CHOICES],
            'multiple' => true,
            'expanded' => true,
            'label' => static::LABEL_ASSIGNEE_COMPANY_BUSINESS_UNITS,
            'disabled' => $this->isAssigneeCompanyBusinessUnitsFieldDisabled($builder),
            'constraints' => [
                new Count([
                    'min' => static::FIELD_ASSIGNEE_COMPANY_BUSINESS_UNITS_MIN_COUNT,
                    'minMessage' => static::FIELD_ASSIGNEE_COMPANY_BUSINESS_UNITS_MIN_MESSAGE,
                ]),
            ],
        ]);

        $builder->get(MerchantRelationRequestTransfer::ASSIGNEE_COMPANY_BUSINESS_UNITS)
            ->addModelTransformer($this->getFactory()->createAssigneeCompanyBusinessUnitsDataTransformer());

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return $this
     */
    protected function addOwnerCompanyBusinessUnitSubform(FormBuilderInterface $builder)
    {
        $builder->add(MerchantRelationRequestTransfer::OWNER_COMPANY_BUSINESS_UNIT, OwnerCompanyBusinessUnitForm::class, [
            'label' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return $this
     */
    protected function addCompanyUserSubform(FormBuilderInterface $builder)
    {
        $builder->add(MerchantRelationRequestTransfer::COMPANY_USER, CompanyUserForm::class, [
            'label' => false,
            'data' => $builder->getData()->getCompanyUser(),
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return bool
     */
    protected function isEditableMerchantRelationRequest(FormBuilderInterface $builder): bool
    {
        return in_array(
            $builder->getData()->getStatus(),
            $this->getConfig()->getEditableMerchantRelationRequestStatuses(),
            true,
        );
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return bool
     */
    protected function isAssigneeCompanyBusinessUnitsFieldDisabled(FormBuilderInterface $builder): bool
    {
        return $builder->getData()->getAssigneeCompanyBusinessUnits()->count() === 1;
    }
}
