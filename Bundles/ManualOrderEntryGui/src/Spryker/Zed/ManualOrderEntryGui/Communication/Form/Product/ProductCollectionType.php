<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Form\Product;

use Generated\Shared\Transfer\ManualOrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\ManualOrderEntryGui\Communication\ManualOrderEntryGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ManualOrderEntryGui\ManualOrderEntryGuiConfig getConfig()
 */
class ProductCollectionType extends AbstractType
{
    /**
     * @var string
     */
    public const TYPE_NAME = 'products';

    /**
     * @var string
     */
    public const FIELD_PRODUCTS = 'products';

    /**
     * @var string
     */
    public const FIELD_IS_PRODUCT_POSTED = 'isProductPosted';

    /**
     * @var string
     */
    public const OPTION_PRODUCT_CLASS_COLLECTION = 'OPTION_PRODUCT_CLASS_COLLECTION';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(static::OPTION_PRODUCT_CLASS_COLLECTION);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addProductsEmptyField($builder, $options)
            ->addIsProductPostedField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addProductsEmptyField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_PRODUCTS, CollectionType::class, [
            'property_path' => QuoteTransfer::MANUAL_ORDER . '.' . ManualOrderTransfer::PRODUCTS,
            'entry_type' => ProductType::class,
            'label' => 'Products',
            'required' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'entry_options' => [
                'data_class' => $options[static::OPTION_PRODUCT_CLASS_COLLECTION],
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addIsProductPostedField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_IS_PRODUCT_POSTED, HiddenType::class, [
            'property_path' => QuoteTransfer::MANUAL_ORDER . '.' . ManualOrderTransfer::IS_PRODUCT_POSTED,
            'data' => 1,
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
