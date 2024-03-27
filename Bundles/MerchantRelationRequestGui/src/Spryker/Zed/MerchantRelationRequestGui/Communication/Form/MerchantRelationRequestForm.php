<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestGui\Communication\Form;

use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\MerchantRelationRequestGui\MerchantRelationRequestGuiConfig getConfig()
 * @method \Spryker\Zed\MerchantRelationRequestGui\Communication\MerchantRelationRequestGuiCommunicationFactory getFactory()
 */
class MerchantRelationRequestForm extends AbstractType
{
    /**
     * @var string
     */
    protected const BLOCK_PREFIX = 'merchantRelationRequest';

    /**
     * @var string
     */
    public const OPTION_ASSIGNEE_BUSINESS_UNITS_CHOICES = 'OPTION_ASSIGNEE_BUSINESS_UNITS_CHOICES';

    /**
     * @var string
     */
    public const BUTTON_APPROVE = 'approve';

    /**
     * @var string
     */
    public const BUTTON_REJECT = 'reject';

    /**
     * @var string
     */
    public const BUTTON_SAVE = 'save';

    /**
     * @var string
     */
    protected const LABEL_BUTTON_APPROVE = 'Approve';

    /**
     * @var string
     */
    protected const LABEL_BUTTON_REJECT = 'Reject';

    /**
     * @var string
     */
    protected const LABEL_BUTTON_SAVE = 'Save';

    /**
     * @var string
     */
    protected const LABEL_ASSIGNEE_COMPANY_BUSINESS_UNITS = 'Assigned Business Units';

    /**
     * @var string
     */
    protected const LABEL_DECISION_NOTE = 'Message to the company';

    /**
     * @var int
     */
    protected const FIELD_DECISION_NOTE_MAX_LENGTH = 5000;

    /**
     * @var int
     */
    protected const FIELD_ASSIGNEE_COMPANY_BUSINESS_UNITS_MIN_COUNT = 1;

    /**
     * @uses \Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig::STATUS_PENDING
     *
     * @var string
     */
    protected const STATUS_PENDING = 'pending';

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
        $this->addAssigneeCompanyBusinessUnitsField($builder, $options)
            ->addDecisionNoteField($builder);

        $this->addFormPreSetDataEventListener($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return void
     */
    protected function addFormPreSetDataEventListener(FormBuilderInterface $builder): void
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
            if (!$event->getData()) {
                return;
            }

            /** @var \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer */
            $merchantRelationRequestTransfer = $event->getData();
            $form = $event->getForm();

            if ($merchantRelationRequestTransfer->getStatus() === static::STATUS_PENDING) {
                $form->add(static::BUTTON_APPROVE, SubmitType::class, [
                    'label' => static::LABEL_BUTTON_APPROVE,
                ]);

                $form->add(static::BUTTON_REJECT, SubmitType::class, [
                    'label' => static::LABEL_BUTTON_REJECT,
                ]);

                $form->add(static::BUTTON_SAVE, SubmitType::class, [
                    'label' => static::LABEL_BUTTON_SAVE,
                ]);
            }
        });
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @param array<mixed> $options
     *
     * @return $this
     */
    protected function addAssigneeCompanyBusinessUnitsField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(MerchantRelationRequestTransfer::ASSIGNEE_COMPANY_BUSINESS_UNITS, Select2ComboBoxType::class, [
            'choices' => $options[static::OPTION_ASSIGNEE_BUSINESS_UNITS_CHOICES],
            'multiple' => true,
            'label' => static::LABEL_ASSIGNEE_COMPANY_BUSINESS_UNITS,
            'required' => false,
            'constraints' => [
                new Count([
                    'min' => static::FIELD_ASSIGNEE_COMPANY_BUSINESS_UNITS_MIN_COUNT,
                    'minMessage' => (new NotBlank())->message,
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
    protected function addDecisionNoteField(FormBuilderInterface $builder)
    {
        $builder->add(MerchantRelationRequestTransfer::DECISION_NOTE, TextareaType::class, [
            'label' => static::LABEL_DECISION_NOTE,
            'sanitize_xss' => true,
            'required' => false,
            'constraints' => [
                new Length(['max' => static::FIELD_DECISION_NOTE_MAX_LENGTH]),
            ],
        ]);

        return $this;
    }
}
