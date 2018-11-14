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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ImageCollectionForm extends AbstractType
{
    public const FIELD_ID_CATEGORY_IMAGE = 'idCategoryImage';
    public const FIELD_IMAGE_SMALL = 'externalUrlSmall';
    public const FIELD_IMAGE_LARGE = 'externalUrlLarge';
    public const FIELD_SORT_ORDER = 'sortOrder';
    public const FIELD_IMAGE_PREVIEW = 'imagePreview';

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
            ->addImageBigField($builder)
            ->addOrderHiddenField($builder);
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
        $builder->add(self::FIELD_ID_CATEGORY_IMAGE, HiddenType::class, []);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addImageSmallField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_IMAGE_SMALL, TextType::class, [
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
    protected function addImagePreviewField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_IMAGE_PREVIEW, ImageType::class, [
            'required' => false,
            'label' => false,
            'property_path' => self::FIELD_IMAGE_SMALL,
            ImageType::OPTION_IMAGE_WIDTH => 150,
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
        $builder->add(self::FIELD_IMAGE_LARGE, TextType::class, [
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
        $builder->add(self::FIELD_SORT_ORDER, HiddenType::class, []);

        return $this;
    }
}
