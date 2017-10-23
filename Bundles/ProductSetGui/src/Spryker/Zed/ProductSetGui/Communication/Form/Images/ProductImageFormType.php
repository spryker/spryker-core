<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Communication\Form\Images;

use Spryker\Zed\Gui\Communication\Form\Type\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductImageFormType extends AbstractType
{
    const FIELD_ID_PRODUCT_IMAGE = 'id_product_image';
    const FIELD_IMAGE_SMALL = 'external_url_small';
    const FIELD_IMAGE_LARGE = 'external_url_large';
    const FIELD_SORT_ORDER = 'sort_order';
    const FIELD_IMAGE_PREVIEW = 'image_preview';
    const FIELD_IMAGE_PREVIEW_LARGE_URL = 'image_preview_large_url';
    const FIELD_FK_IMAGE_SET_ID = 'fk_image_set_id';

    const OPTION_IMAGE_PREVIEW_LARGE_URL = 'option_image_preview_large_url';

    /**
     * @return string
     */
    public function getName()
    {
        return 'product_image_collection';
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
            ->addProductImageIdHiddenField($builder)
            ->addProductImageLargeUrlHiddenField($builder)
            ->addImageSetIdHiddenField($builder)
            ->addImagePreviewField($builder)
            ->addImageSmallField($builder)
            ->addImageBigField($builder)
            ->addOrderHiddenField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductImageIdHiddenField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_PRODUCT_IMAGE, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductImageLargeUrlHiddenField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_IMAGE_PREVIEW_LARGE_URL, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addImageSetIdHiddenField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FK_IMAGE_SET_ID, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addImagePreviewField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_IMAGE_PREVIEW, ImageType::class, [
            'label' => false,
            ImageType::OPTION_IMAGE_WIDTH => 150,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addImageSmallField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_IMAGE_SMALL, TextType::class, [
            'label' => 'Small Image URL *',
            'required' => true,
            'constraints' => [
                new NotBlank(),
                new Length([
                    'min' => 0,
                    'max' => 2048,
                ]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addImageBigField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_IMAGE_LARGE, TextType::class, [
            'label' => 'Large Image URL *',
            'required' => true,
            'constraints' => [
                new NotBlank(),
                new Length([
                    'min' => 0,
                    'max' => 2048,
                ]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addOrderHiddenField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_SORT_ORDER, HiddenType::class);

        return $this;
    }
}
