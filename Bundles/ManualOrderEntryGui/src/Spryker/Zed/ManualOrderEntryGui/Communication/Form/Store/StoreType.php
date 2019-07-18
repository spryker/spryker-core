<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Form\Store;

use Generated\Shared\Transfer\ManualOrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\ManualOrderEntryGui\Communication\ManualOrderEntryGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ManualOrderEntryGui\ManualOrderEntryGuiConfig getConfig()
 */
class StoreType extends AbstractType
{
    public const TYPE_NAME = 'stores';

    public const FIELD_STORE = 'storeCurrency';

    public const OPTION_STORES_ARRAY = 'option-stores-array';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addStoreField(
            $builder,
            $options[static::OPTION_STORES_ARRAY]
        );
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(static::OPTION_STORES_ARRAY);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $storesList
     *
     * @return $this
     */
    protected function addStoreField(FormBuilderInterface $builder, array $storesList)
    {
        $builder->add(static::FIELD_STORE, Select2ComboBoxType::class, [
            'property_path' => QuoteTransfer::MANUAL_ORDER . '.' . ManualOrderTransfer::STORE_CURRENCY,
            'label' => 'Store and Currency',
            'choices' => array_flip($storesList),
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
