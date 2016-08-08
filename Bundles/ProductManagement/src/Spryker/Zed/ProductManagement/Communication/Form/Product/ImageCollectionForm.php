<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Product;

use Spryker\Zed\Gui\Communication\Form\Type\ImageType;
use Spryker\Zed\ProductManagement\Communication\Form\AbstractSubForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ImageCollectionForm extends AbstractSubForm
{

    const FIELD_ID_PRODUCT_IMAGE = 'id_product_image';
    const FIELD_IMAGE_SMALL = 'external_url_small';
    const FIELD_IMAGE_LARGE = 'external_url_large';
    const FIELD_SORT_ORDER = 'sort_order';

    const FIELD_IMAGE_PREVIEW = 'image_preview';


    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $this
            ->addProductImageIdHiddenField($builder)
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
        $builder
            ->add(self::FIELD_ID_PRODUCT_IMAGE, 'hidden', []);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addImagePreviewField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_IMAGE_PREVIEW, new ImageType(), [
                'required' => false,
                'label' => false,
                'img_width' => 150,
                'img_title' => 'foo',
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
        $builder
            ->add(self::FIELD_IMAGE_SMALL, 'text', [
                'required' => true,
                'label' => 'Small',
                'constraints' => [
                    new NotBlank(),
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
        $builder
            ->add(self::FIELD_IMAGE_LARGE, 'text', [
                'required' => true,
                'label' => 'Large',
                'constraints' => [
                    new NotBlank(),
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
        $builder
            ->add(self::FIELD_SORT_ORDER, 'hidden', []);

        return $this;
    }

}
