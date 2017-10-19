<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Product;

use Spryker\Zed\Gui\Communication\Form\Type\ImageType;
use Spryker\Zed\ProductManagement\Communication\Form\AbstractSubForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ImageCollectionForm extends AbstractSubForm
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
        parent::buildForm($builder, $options);

        $this
            ->addProductImageIdHiddenField($builder, $options)
            ->addProductImageLargeUrlHiddenField($builder, $options)
            ->addImageSetIdHiddenField($builder, $options)
            ->addImagePreviewField($builder, $options)
            ->addImageSmallField($builder, $options)
            ->addImageBigField($builder, $options)
            ->addOrderHiddenField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addProductImageIdHiddenField(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add(self::FIELD_ID_PRODUCT_IMAGE, 'hidden', []);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addProductImageLargeUrlHiddenField(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add(self::FIELD_IMAGE_PREVIEW_LARGE_URL, 'hidden', []);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addImageSetIdHiddenField(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add(self::FIELD_FK_IMAGE_SET_ID, 'hidden', []);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addImagePreviewField(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add(self::FIELD_IMAGE_PREVIEW, new ImageType(), [
                'required' => false,
                'label' => false,
                ImageType::OPTION_IMAGE_WIDTH => 150,
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addImageSmallField(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add(self::FIELD_IMAGE_SMALL, 'text', [
                'required' => true,
                'label' => 'Small',
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
     * @param array $options
     *
     * @return $this
     */
    protected function addImageBigField(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add(self::FIELD_IMAGE_LARGE, 'text', [
                'required' => true,
                'label' => 'Large',
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
     * @param array $options
     *
     * @return $this
     */
    protected function addOrderHiddenField(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add(self::FIELD_SORT_ORDER, 'hidden', []);

        return $this;
    }
}
