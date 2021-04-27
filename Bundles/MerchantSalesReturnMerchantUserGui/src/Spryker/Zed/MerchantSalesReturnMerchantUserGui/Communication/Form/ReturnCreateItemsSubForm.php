<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Form;

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
 * @method \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\MerchantSalesReturnMerchantUserGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantSalesReturnMerchantUserGui\MerchantSalesReturnMerchantUserGuiConfig getConfig()
 */
class ReturnCreateItemsSubForm extends AbstractType
{
    protected const MESSAGE_RETURN_ITEM_IS_NOT_ELIGIBLE_FOR_RETURN = 'Item selected for return is not eligible for return anymore.';

    protected const PLACEHOLDER_SELECT_REASON = 'Select reason';

    protected const FIELD_CUSTOM_REASON = 'customReason';

    /**
     * @uses \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Form\ReturnCreateForm::OPTION_RETURN_REASONS
     */
    protected const OPTION_RETURN_REASONS = 'option_return_reasons';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            static::OPTION_RETURN_REASONS,
        ]);
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @phpstan-param array<string, mixed> $options
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addIsReturnableField($builder)
            ->addReasonField($builder, $options)
            ->addCustomReasonField($builder);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, $this->getReturnItemPreSubmitCallback());
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsReturnableField(FormBuilderInterface $builder)
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
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @phpstan-param array<string, mixed> $options
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addReasonField(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {
            /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
            $itemTransfer = $event->getData()[ReturnItemTransfer::ORDER_ITEM];

            $event->getForm()->add(ReturnItemTransfer::REASON, ChoiceType::class, [
                'label' => false,
                'placeholder' => static::PLACEHOLDER_SELECT_REASON,
                'choices' => $options[static::OPTION_RETURN_REASONS],
                'required' => false,
                'disabled' => !$itemTransfer->getIsReturnable(),
            ]);
        });

        return $this;
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
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
