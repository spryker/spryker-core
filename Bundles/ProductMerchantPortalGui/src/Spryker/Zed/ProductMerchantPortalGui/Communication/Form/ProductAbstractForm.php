<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiConfig getConfig()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 */
class ProductAbstractForm extends AbstractType
{
    public const OPTION_STORE_CHOICES = 'OPTION_STORE_CHOICES';
    public const OPTION_PRODUCT_CATEGORY_CHOICES = 'OPTION_PRODUCT_CATEGORY_CHOICES';

    public const BLOCK_PREFIX = 'productAbstract';

    protected const FIELD_STORES = 'stores';

    protected const LABEL_STORES = 'Stores';
    protected const LABEL_CATEGORIES = 'Categories';

    protected const PLACEHOLDER_STORES = 'Select';
    protected const PLACEHOLDER_CATEGORIES = 'Select';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::BLOCK_PREFIX;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductAbstractTransfer::class,
        ]);

        $resolver->setRequired(static::OPTION_STORE_CHOICES);
        $resolver->setRequired(static::OPTION_PRODUCT_CATEGORY_CHOICES);
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @phpstan-param array<mixed> $options
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addLocalizedAttributesSubform($builder)
            ->addStoresField($builder, $options)
            ->addPrices($builder, $options)
            ->addCategories($builder, $options);

        $this->executeProductAbstractFormExpanderPlugins($builder, $options);
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addLocalizedAttributesSubform(FormBuilderInterface $builder)
    {
        $builder->add(ProductAbstractTransfer::LOCALIZED_ATTRIBUTES, CollectionType::class, [
            'label' => false,
            'entry_type' => ProductLocalizedAttributesForm::class,
            'allow_add' => true,
            'allow_delete' => true,
        ]);

        return $this;
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @phpstan-param array<mixed> $options
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addStoresField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_STORES,
            ChoiceType::class,
            [
                'choices' => $options[static::OPTION_STORE_CHOICES],
                'multiple' => true,
                'label' => static::LABEL_STORES,
                'required' => false,
                'empty_data' => $builder->getData()->getStoreRelation()->getIdStores(),
                'attr' => [
                    'placeholder' => static::PLACEHOLDER_STORES,
                ],
                'property_path' => 'storeRelation.idStores',
            ]
        );

        return $this;
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @phpstan-param array<mixed> $options
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addPrices(FormBuilderInterface $builder, array $options)
    {
        $builder->add(ProductAbstractTransfer::PRICES, HiddenType::class, [
            'required' => false,
            'label' => false,
        ]);

        $idProductAbstract = $options['data']->getIdProductAbstract();
        $priceProductTransformer = $this->getFactory()->createPriceProductTransformer($idProductAbstract);

        $builder->get(ProductAbstractTransfer::PRICES)->addModelTransformer($priceProductTransformer);

        return $this;
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @phpstan-param array<mixed> $options
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addCategories(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            ProductAbstractTransfer::CATEGORY_IDS,
            ChoiceType::class,
            [
                'choices' => $options[static::OPTION_PRODUCT_CATEGORY_CHOICES],
                'multiple' => true,
                'label' => static::LABEL_CATEGORIES,
                'required' => false,
                'empty_data' => $builder->getData()->getCategoryIds(),
                'attr' => [
                    'placeholder' => static::PLACEHOLDER_CATEGORIES,
                ],
            ]
        );

        return $this;
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @phpstan-param array<mixed> $options
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function executeProductAbstractFormExpanderPlugins(FormBuilderInterface $builder, array $options)
    {
        foreach ($this->getFactory()->getProductAbstractFormExpanderPlugins() as $productAbstractFormExpanderPlugin) {
            $builder = $productAbstractFormExpanderPlugin->expand($builder, $options);
        }

        return $this;
    }
}
