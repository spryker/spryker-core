<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\ProductManagement\Communication\Form\Product\Concrete\ConcreteGeneralForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\Concrete\StockForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\Price\ProductMoneyCollectionType;
use Spryker\Zed\ProductManagement\Communication\Form\Product\Price\ProductMoneyType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductConcreteFormEdit extends ProductFormAdd
{
    const FIELD_ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    const FIELD_ID_PRODUCT_CONCRETE = 'id_product';

    const FORM_ASSIGNED_BUNDLED_PRODUCTS = 'assigned_bundled_products';
    const BUNDLED_PRODUCTS_TO_BE_REMOVED = 'product_bundles_to_be_removed';

    const OPTION_IS_BUNDLE_ITEM = 'is_bundle_item';

    /**
     * @return string
     */
    public function getName()
    {
        return 'ProductConcreteFormEdit';
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
            ->addSkuField($builder)
            ->addProductAbstractIdHiddenField($builder)
            ->addProductConcreteIdHiddenField($builder)
            ->addGeneralLocalizedForms($builder)
            ->addPriceForm($builder, $options)
            ->addStockForm($builder, $options)
            ->addImageLocalizedForms($builder)
            ->addAssignBundledProductForm($builder, $options)
            ->addBundledProductsToBeRemoved($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addBundledProductsToBeRemoved(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::BUNDLED_PRODUCTS_TO_BE_REMOVED, 'hidden', [
                'attr' => [
                    'id' => self::BUNDLED_PRODUCTS_TO_BE_REMOVED,
                ],
            ]);

        $builder->get(self::BUNDLED_PRODUCTS_TO_BE_REMOVED)
            ->addModelTransformer(new CallbackTransformer(
                function ($value) {
                    if ($value) {
                        return implode(',', $value);
                    }
                },
                function ($bundledProductsToBeRemoved) {
                    if (!$bundledProductsToBeRemoved) {
                        return [];
                    }

                    return explode(',', $bundledProductsToBeRemoved);
                }
            ));

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSkuField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_SKU, 'text', [
                'label' => 'SKU',
                'read_only' => true,
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductAbstractIdHiddenField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_ID_PRODUCT_ABSTRACT, 'hidden', []);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductConcreteIdHiddenField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_ID_PRODUCT_CONCRETE, 'hidden', []);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addPriceForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add(
            static::FIELD_PRICES,
            ProductMoneyCollectionType::class,
            [
                'entry_options' => [
                    'data_class' => PriceProductTransfer::class,
                ],
                'entry_type' => ProductMoneyType::class,
            ]
        );
        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addAssignBundledProductForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FORM_ASSIGNED_BUNDLED_PRODUCTS, 'collection', [
            'type' => new BundledProductForm(),
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addStockForm(FormBuilderInterface $builder, array $options = [])
    {
        if (isset($options[static::OPTION_IS_BUNDLE_ITEM]) && $options[static::OPTION_IS_BUNDLE_ITEM] === true) {
            return $this;
        }

        $builder
            ->add(self::FORM_PRICE_AND_STOCK, 'collection', [
                'type' => new StockForm(),
                'label' => false,
            ]);

        return $this;
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Communication\Form\Product\GeneralForm
     */
    protected function createGeneralForm()
    {
        return new ConcreteGeneralForm();
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(self::OPTION_IS_BUNDLE_ITEM);
    }
}
