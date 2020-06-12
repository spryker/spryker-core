<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnGui\Communication\Form;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ReturnItemTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\SalesReturnGui\Communication\SalesReturnGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesReturnGui\SalesReturnGuiConfig getConfig()
 */
class ReturnCreateItemsSubForm extends AbstractType
{
    protected const MESSAGE_RETURN_ITEM_IS_NOT_ELIGIBLE_FOR_RETURN = 'Item selected for return is not eligible for return anymore.';

    public const FIELD_CUSTOM_REASON = 'customReason';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            ReturnCreateForm::OPTION_RETURN_REASONS,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addIsReturnable($builder)
            ->addReason($builder, $options)
            ->addCustomReasonField($builder);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, $this->getReturnItemPreSubmitCallback());
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsReturnable(FormBuilderInterface $builder)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
            $itemTransfer = $event->getData()[ReturnItemTransfer::ORDER_ITEM];

            $event->getForm()->add(ItemTransfer::IS_RETURNABLE, CheckboxType::class, [
                'label' => false,
                'required' => false,
                'disabled' => !$itemTransfer->getIsReturnable(),
            ]);
        });

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addReason(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {
            /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
            $itemTransfer = $event->getData()[ReturnItemTransfer::ORDER_ITEM];

            $event->getForm()->add(ReturnItemTransfer::REASON, ChoiceType::class, [
                'label' => false,
                'placeholder' => 'Select reason',
                'choices' => $options[ReturnCreateForm::OPTION_RETURN_REASONS],
                'required' => false,
                'disabled' => !$itemTransfer->getIsReturnable(),
            ]);
        });

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCustomReasonField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CUSTOM_REASON, TextareaType::class, [
            'label' => false,
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @return \Closure
     */
    protected function getReturnItemPreSubmitCallback(): callable
    {
        return function (FormEvent $formEvent) {
            $form = $formEvent->getForm();

            /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
            $itemTransfer = $form->getData()[ReturnItemTransfer::ORDER_ITEM];
            $isChecked = $formEvent->getData()[ItemTransfer::IS_RETURNABLE] ?? false;

            if ($isChecked && !$itemTransfer->getIsReturnable()) {
                $form->addError(new FormError(static::MESSAGE_RETURN_ITEM_IS_NOT_ELIGIBLE_FOR_RETURN));
            }
        };
    }
}
