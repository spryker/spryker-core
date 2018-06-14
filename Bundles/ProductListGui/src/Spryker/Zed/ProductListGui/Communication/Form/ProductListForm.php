<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Form;

use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductListForm extends AbstractType
{
    public const FIELD_GENERAL = 'productList';
    public const FIELD_CATEGORIES = 'categories';

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'productList';
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
            ->addGeneralSubForm($builder)
            ->addCategoriesSubForm($builder, $options[static::FIELD_CATEGORIES]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addGeneralSubForm(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_GENERAL, ProductListGeneralType::class);

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
        $builder->add(static::FIELD_CATEGORIES, CategoriesType::class, $options);

        return $this;
    }
}
