<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Form;

use Generated\Shared\Transfer\ProductListCategoryRelationTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductListCategoryRelationType extends AbstractType
{
    const FIELD_CATEGORIES = ProductListCategoryRelationTransfer::CATEGORY_IDS;
    const OPTION_CATEGORY_ARRAY = 'option-category-array';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'categories';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired(static::OPTION_CATEGORY_ARRAY);

        $resolver->setDefaults([
            'data_class' => ProductListCategoryRelationTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addCategoryField($builder, $options[static::OPTION_CATEGORY_ARRAY]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $categoryList
     *
     * @return $this
     */
    protected function addCategoryField(FormBuilderInterface $builder, array $categoryList): self
    {
        $builder->add(static::FIELD_CATEGORIES, Select2ComboBoxType::class, [
            'property_path' => static::FIELD_CATEGORIES,
            'label' => 'Categories',
            'choices' => $categoryList,
            'choices_as_values' => true,
            'multiple' => true,
            'required' => false,
        ]);

        return $this;
    }
}
