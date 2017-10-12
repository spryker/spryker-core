<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Communication\Form;

use Spryker\Zed\ProductSetGui\Communication\Form\General\GeneralFormType;
use Spryker\Zed\ProductSetGui\Communication\Form\Images\ImagesFormType;
use Spryker\Zed\ProductSetGui\Communication\Form\Products\ProductsFormType;
use Spryker\Zed\ProductSetGui\Communication\Form\Seo\SeoFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateProductSetFormType extends AbstractType
{
    const FIELD_GENERAL_FORM = 'general_form';
    const FIELD_SEO_FORM = 'seo_form';
    const FIELD_IMAGES_FORM = 'images_form';
    const FIELD_PRODUCTS_FORM = 'products_form';

    /**
     * @return string
     */
    public function getName()
    {
        return 'product_set_form';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'required' => false,
        ]);
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
            ->addGeneralForm($builder)
            ->addProductsForm($builder)
            ->addSeoForm($builder)
            ->addImagesForm($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addGeneralForm(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_GENERAL_FORM, GeneralFormType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductsForm(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PRODUCTS_FORM, ProductsFormType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSeoForm(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_SEO_FORM, SeoFormType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addImagesForm(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_IMAGES_FORM, ImagesFormType::class);

        return $this;
    }
}
