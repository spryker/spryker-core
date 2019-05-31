<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageGui\Communication\Form;

use Generated\Shared\Transfer\CategoryImageTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\ImageType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\CategoryImageGui\Communication\CategoryImageGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CategoryImageGui\CategoryImageGuiConfig getConfig()
 */
class ImageCollectionForm extends AbstractType
{
    public const FIELD_ID_CATEGORY_IMAGE = 'idCategoryImage';
    public const FIELD_IMAGE_SMALL = 'externalUrlSmall';
    public const FIELD_IMAGE_LARGE = 'externalUrlLarge';
    public const FIELD_SORT_ORDER = 'sortOrder';
    public const FIELD_IMAGE_PREVIEW = 'imagePreview';
    public const FIELD_IMAGE_PREVIEW_LARGE = 'imagePreviewLarge';

    public const IMAGE_URL_MIN_LENGTH = 0;
    public const IMAGE_URL_MAX_LENGTH = 2048;
    public const IMAGE_PREVIEW_WIDTH = 150;

    protected const MAX_SORT_ORDER_VALUE = 2147483647; // 32 bit integer
    protected const MIN_SORT_ORDER_VALUE = 0;
    protected const DEFAULT_SORT_ORDER_VALUE = 0;

    /**
     * @uses \Spryker\Zed\Gui\Communication\Form\Type\ImageType::OPTION_IMAGE_WIDTH
     */
    protected const OPTION_IMAGE_WIDTH = 'image_width';

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
            ->addImagePreviewField($builder)
            ->addImageSmallField($builder)
            ->addImagePreviewLargeField($builder)
            ->addImageBigField($builder)
            ->addSortOrderField($builder);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'category_image_collection';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => CategoryImageTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCategoryImageIdHiddenField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_CATEGORY_IMAGE, HiddenType::class, []);

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
            'required' => true,
            'label' => 'Small Image URL',
            'constraints' => [
                new NotBlank(),
                new Length([
                    'min' => static::IMAGE_URL_MIN_LENGTH,
                    'max' => static::IMAGE_URL_MAX_LENGTH,
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
    protected function addImagePreviewField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_IMAGE_PREVIEW, ImageType::class, [
            'required' => false,
            'label' => false,
            'property_path' => static::FIELD_IMAGE_SMALL,
            static::OPTION_IMAGE_WIDTH => static::IMAGE_PREVIEW_WIDTH,
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
            'required' => true,
            'label' => 'Large Image URL',
            'constraints' => [
                new NotBlank(),
                new Length([
                    'min' => static::IMAGE_URL_MIN_LENGTH,
                    'max' => static::IMAGE_URL_MAX_LENGTH,
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
    protected function addImagePreviewLargeField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_IMAGE_PREVIEW_LARGE, ImageType::class, [
            'required' => false,
            'label' => false,
            'property_path' => static::FIELD_IMAGE_LARGE,
            static::OPTION_IMAGE_WIDTH => static::IMAGE_PREVIEW_WIDTH,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSortOrderField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_SORT_ORDER, NumberType::class, [
            'constraints' => [
                new NotBlank(),
                new LessThanOrEqual([
                    'value' => static::MAX_SORT_ORDER_VALUE,
                ]),
                new GreaterThanOrEqual([
                    'value' => static::MIN_SORT_ORDER_VALUE,
                ]),
            ],
            'attr' => [
                'data-sort-order' => static::DEFAULT_SORT_ORDER_VALUE,
            ],
        ]);

        return $this;
    }
}
