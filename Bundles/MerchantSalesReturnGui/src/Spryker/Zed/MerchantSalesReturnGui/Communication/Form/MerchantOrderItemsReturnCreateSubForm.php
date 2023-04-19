<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnGui\Communication\Form;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ReturnItemTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\MerchantSalesReturnGui\Communication\Form\DataProvider\MerchantSalesReturnCreateFormDataProvider;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\MerchantSalesReturnGui\Communication\MerchantSalesReturnGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantSalesReturnGui\MerchantSalesReturnGuiConfig getConfig()
 */
class MerchantOrderItemsReturnCreateSubForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_IS_RETURNABLE = ItemTransfer::IS_RETURNABLE;

    /**
     * @var string
     */
    public const FIELD_REASON = ReturnItemTransfer::REASON;

    /**
     * @uses \Spryker\Zed\SalesReturnGui\Communication\Form\ReturnCreateItemsSubForm::FIELD_CUSTOM_REASON
     *
     * @var string
     */
    public const FIELD_CUSTOM_REASON = 'customReason';

    /**
     * @var string
     */
    protected const FIELD_REASON_PLACEHOLDER = 'Select reason';

    /**
     * @uses \Spryker\Zed\SalesReturnGui\Communication\Form\ReturnCreateForm::OPTION_RETURN_REASONS
     *
     * @var string
     */
    protected const OPTION_RETURN_REASONS = 'option_return_reasons';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([static::OPTION_RETURN_REASONS]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addIsReturnableField($builder)
            ->addReasonField($builder, $options)
            ->addCustomReasonField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addReasonField(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options): void {
            $itemTransfer = $event->getData()[MerchantSalesReturnCreateFormDataProvider::MERCHANT_ORDER_DATA_ORDER_ITEM_KEY];

            $event->getForm()->add(static::FIELD_REASON, ChoiceType::class, [
                'label' => false,
                'placeholder' => static::FIELD_REASON_PLACEHOLDER,
                'choices' => $options[static::OPTION_RETURN_REASONS],
                'required' => false,
                'disabled' => !$itemTransfer->getIsReturnable(),
            ]);
        });

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
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
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return $this
     */
    protected function addIsReturnableField(FormBuilderInterface $builder)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
            $itemTransfer = $event->getData()[MerchantSalesReturnCreateFormDataProvider::MERCHANT_ORDER_DATA_ORDER_ITEM_KEY];

            $event->getForm()->add(static::FIELD_IS_RETURNABLE, CheckboxType::class, [
                'label' => false,
                'required' => false,
                'disabled' => !$itemTransfer->getIsReturnable(),
            ]);
        });

        return $this;
    }
}
