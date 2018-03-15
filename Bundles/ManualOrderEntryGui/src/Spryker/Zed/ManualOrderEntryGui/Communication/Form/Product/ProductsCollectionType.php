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
class ProductsCollectionType extends AbstractType
{

    const FIELD_PRODUCTS = 'manualOrderProducts';

    const OPTION_DATA_CLASS_COLLECTION = 'data_class_collection';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(static::OPTION_DATA_CLASS_COLLECTION);
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
            ->addProductsField($builder, $options)
        ;

        // @todo @Artem
//        $builder->addEventListener(FormEvents::POST_SUBMIT,
//            function (FormEvent $event) {
//                /** @var QuoteTransfer $quoteTransfer */
//                $quoteTransfer = $event->getData();
//                $quote = $event->getForm()->getData();
//                // Set Items Here
//                $productMapper = $this->getFactory()->createProductMapper();
//                $productMapper->mapSkusToItemsTransfer($quoteTransfer);
//            });
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addProductsField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_PRODUCTS, CollectionType::class, [
            'entry_type' => ProductType::class,
            'label' => 'Products',
            'required' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'entry_options' => [
                'data_class' => $options[static::OPTION_DATA_CLASS_COLLECTION],
            ],
        ]);

        $builder->get(static::FIELD_PRODUCTS)
            ->addModelTransformer($this->createArrayObjectModelTransformer());

        return $this;
    }

    /**
     * @return \Symfony\Component\Form\CallbackTransformer
     */
    protected function createArrayObjectModelTransformer()
    {
        return new CallbackTransformer(
            function ($value) {
                return (array)$value;
            },
            function ($value) {
                return new ArrayObject($value);
            }
        );
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'products';
    }

}
