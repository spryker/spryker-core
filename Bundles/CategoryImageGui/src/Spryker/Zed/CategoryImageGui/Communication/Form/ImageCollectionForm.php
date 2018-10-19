<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageGui\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\ImageType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ImageCollectionForm extends AbstractType
{
    public const FIELD_ID_PRODUCT_IMAGE = 'id_category_image';
    public const FIELD_IMAGE_SMALL = 'external_url_small';
    public const FIELD_IMAGE_LARGE = 'external_url_large';
    public const FIELD_SORT_ORDER = 'sort_order';
    public const FIELD_IMAGE_PREVIEW = 'image_preview';
    public const FIELD_IMAGE_PREVIEW_LARGE_URL = 'imagePreviewLargeUrl';
    public const FIELD_FK_IMAGE_SET_ID = 'fk_image_set_id';

    public const OPTION_IMAGE_PREVIEW_LARGE_URL = 'option_image_preview_large_url';

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
            ->addCategoryImageIdHiddenField($builder)
            ->addCategoryImageLargeUrlHiddenField($builder)
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
    protected function addCategoryImageIdHiddenField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_ID_PRODUCT_IMAGE, HiddenType::class, []);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCategoryImageLargeUrlHiddenField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_IMAGE_PREVIEW_LARGE_URL, HiddenType::class, []);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addImageSetIdHiddenField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_FK_IMAGE_SET_ID, HiddenType::class, []);

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
            ->add(self::FIELD_IMAGE_PREVIEW, ImageType::class, [
                'required' => false,
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
        $builder
            ->add(self::FIELD_IMAGE_SMALL, TextType::class, [
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
     *
     * @return $this
     */
    protected function addImageBigField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_IMAGE_LARGE, TextType::class, [
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
     *
     * @return $this
     */
    protected function addOrderHiddenField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_SORT_ORDER, HiddenType::class, []);

        return $this;
    }
}
