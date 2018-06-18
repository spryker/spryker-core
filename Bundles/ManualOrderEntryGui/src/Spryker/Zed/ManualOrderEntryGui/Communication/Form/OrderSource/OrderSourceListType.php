<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Form\OrderSource;

use Generated\Shared\Transfer\OrderSourceTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\ManualOrderEntryGui\Communication\ManualOrderEntryGuiCommunicationFactory getFactory()
 */
class OrderSourceListType extends AbstractType
{
    public const TYPE_NAME = 'order-source-list';

    public const FIELD_ORDER_SOURCE = 'id_order_source';

    public const OPTION_ORDER_SOURCE_ARRAY = 'option-order-source-array';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addOrderSourceField(
            $builder,
            $options[static::OPTION_ORDER_SOURCE_ARRAY]
        );
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired(static::OPTION_ORDER_SOURCE_ARRAY);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $orderSourceList
     *
     * @return $this
     */
    protected function addOrderSourceField(FormBuilderInterface $builder, array $orderSourceList): self
    {
        $builder->add(static::FIELD_ORDER_SOURCE, Select2ComboBoxType::class, [
            'property_path' => QuoteTransfer::ORDER_SOURCE . '.' . OrderSourceTransfer::ID_ORDER_SOURCE,
            'label' => 'Order Source',
            'choices' => array_flip($orderSourceList),
            'multiple' => false,
            'required' => true,
        ]);

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::TYPE_NAME;
    }
}
