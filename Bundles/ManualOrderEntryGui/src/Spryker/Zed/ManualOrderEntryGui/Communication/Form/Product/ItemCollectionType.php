<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Form\Product;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;

/**
 * @method \Spryker\Zed\ManualOrderEntryGui\Communication\ManualOrderEntryGuiCommunicationFactory getFactory()
 */
class ItemCollectionType extends AbstractType
{

    const FIELD_ITEMS = 'items';

    const OPTION_ITEM_CLASS_COLLECTION = 'item_class_collection';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(static::OPTION_ITEM_CLASS_COLLECTION);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addItemsField($builder, $options)
        ;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addItemsField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_ITEMS, CollectionType::class, [
            'entry_type' => ItemType::class,
            'label' => 'Added Items',
            'required' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'entry_options' => [
                'data_class' => $options[static::OPTION_ITEM_CLASS_COLLECTION],
            ],
        ]);

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'items';
    }

}
