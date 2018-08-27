<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Form;

use Generated\Shared\Transfer\ProductListCategoryRelationTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductListCategoryRelationFormType extends AbstractType
{
    public const FIELD_ID_PRODUCT_LIST = ProductListCategoryRelationTransfer::ID_PRODUCT_LIST;
    public const FIELD_CATEGORY_IDS = ProductListCategoryRelationTransfer::CATEGORY_IDS;
    public const BLOCK_PREFIX = 'productListCategoryRelation';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => ProductListCategoryRelationTransfer::class,
            'label' => false,
        ]);

        $resolver->setRequired(ProductListAggregateFormType::OPTION_CATEGORY_IDS);
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::BLOCK_PREFIX;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addIdProductListField($builder)
            ->addCategoryIdsField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdProductListField(FormBuilderInterface $builder): self
    {
        $builder->add(
            static::FIELD_ID_PRODUCT_LIST,
            HiddenType::class
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addCategoryIdsField(FormBuilderInterface $builder, array $options): self
    {
        $builder->add(static::FIELD_CATEGORY_IDS, Select2ComboBoxType::class, [
            'label' => 'Categories',
            'choices' => array_flip($options[ProductListAggregateFormType::OPTION_CATEGORY_IDS]),
            'choices_as_values' => true,
            'multiple' => true,
            'required' => false,
        ]);

        return $this;
    }
}
