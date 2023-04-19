<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\MerchantSalesReturnGui\Communication\MerchantSalesReturnGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantSalesReturnGui\MerchantSalesReturnGuiConfig getConfig()
 */
class MerchantOrderReturnCreateSubForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_RETURN_MERCHANT_ORDER_ITEMS = 'returnMerchantOrderItems';

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
     * @return \Symfony\Component\Form\FormBuilderInterface<mixed>
     */
    public function buildForm(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $this->addReturnMerchantOrderItemsField($builder, $options);

        return $builder;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addReturnMerchantOrderItemsField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_RETURN_MERCHANT_ORDER_ITEMS,
            CollectionType::class,
            [
                'entry_type' => MerchantOrderItemsReturnCreateSubForm::class,
                'entry_options' => [
                    static::OPTION_RETURN_REASONS => $options[static::OPTION_RETURN_REASONS],
                ],
                'label' => false,
            ],
        );

        return $this;
    }
}
