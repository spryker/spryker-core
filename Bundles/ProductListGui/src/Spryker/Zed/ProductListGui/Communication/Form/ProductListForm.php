<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Form;

use Generated\Shared\Transfer\ProductListTransfer;
use Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

class ProductListForm extends AbstractType
{
    public const FIELD_ID = ProductListTransfer::ID_PRODUCT_LIST;
    public const FIELD_NAME = ProductListTransfer::TITLE;
    public const FIELD_TYPE = ProductListTransfer::TYPE;
    public const FIELD_CATEGORIES = ProductListTransfer::PRODUCT_LIST_CATEGORY_RELATION;
    public const FIELD_PRODUCTS = ProductListTransfer::PRODUCT_LIST_PRODUCT_CONCRETE_RELATION;

    public const BLOCK_PREFIX = 'productList';

    /**
     * @return string
     */
    public function getBlockPrefix()
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
        $resolver->setRequired([
            static::FIELD_CATEGORIES,
            static::FIELD_PRODUCTS,
        ]);

        $resolver->setDefaults([
            'data_class' => ProductListTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string[] $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addIdField($builder)
            ->addNameField($builder)
            ->addTypeFiled($builder)
            ->addCategoriesSubForm($builder, $options[static::FIELD_CATEGORIES])
            ->addProductsSubForm($builder, $options[static::FIELD_PRODUCTS]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_ID, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_NAME, TextType::class, [
            'label' => 'Name',
            'constraints' => [
                new Required(),
                new NotBlank(),
                new Length(['max' => 100]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addTypeFiled(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_TYPE, ChoiceType::class, [
            'label' => 'Type',
            'expanded' => true,
            'multiple' => false,
            'choices' => [
                'Whitelist' => SpyProductListTableMap::COL_TYPE_WHITELIST,
                'Blacklist' => SpyProductListTableMap::COL_TYPE_BLACKLIST,
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addCategoriesSubForm(FormBuilderInterface $builder, array $options): self
    {
        $builder->add(static::FIELD_CATEGORIES, ProductListCategoryRelationType::class, $options);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addProductsSubForm(FormBuilderInterface $builder, array $options): self
    {
        $builder->add(static::FIELD_PRODUCTS, ProductListProductConcreteRelationType::class, $options);

        return $this;
    }
}
