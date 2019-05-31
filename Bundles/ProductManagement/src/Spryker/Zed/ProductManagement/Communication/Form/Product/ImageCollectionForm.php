<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Product;

use Spryker\Zed\Gui\Communication\Form\Type\ImageType;
use Spryker\Zed\ProductManagement\Communication\Form\AbstractSubForm;
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
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface getQueryContainer()
 */
class ImageCollectionForm extends AbstractSubForm
{
    public const FIELD_ID_PRODUCT_IMAGE = 'id_product_image';
    public const FIELD_IMAGE_SMALL = 'external_url_small';
    public const FIELD_IMAGE_LARGE = 'external_url_large';
    public const FIELD_SORT_ORDER = 'sort_order';
    public const FIELD_IMAGE_PREVIEW = 'image_preview';
    public const FIELD_IMAGE_PREVIEW_LARGE_URL = 'image_preview_large_url';
    public const FIELD_FK_IMAGE_SET_ID = 'fk_image_set_id';

    public const OPTION_IMAGE_PREVIEW_LARGE_URL = 'option_image_preview_large_url';

    protected const MAX_SORT_ORDER_VALUE = 2147483647; // 32 bit integer
    protected const MIN_SORT_ORDER_VALUE = 0;
    protected const DEFAULT_SORT_ORDER_VALUE = 0;

    /**
     * @uses \Spryker\Zed\Gui\Communication\Form\Type\ImageType::OPTION_IMAGE_WIDTH
     */
    protected const OPTION_IMAGE_WIDTH = 'image_width';

    /**
     * @return string
     */
    public function getBlockPrefix()
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
            ->addProductImageLargeUrlField($builder, $options)
            ->addImageSetIdHiddenField($builder, $options)
            ->addImagePreviewField($builder, $options)
            ->addImageSmallField($builder, $options)
            ->addImageBigField($builder, $options)
            ->addSortOrderField($builder, $options);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'attr' => [
                'class' => 'image-collection',
            ],
        ]);
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
            ->add(self::FIELD_ID_PRODUCT_IMAGE, HiddenType::class, []);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addProductImageLargeUrlField(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add(static::FIELD_IMAGE_PREVIEW_LARGE_URL, ImageType::class, [
            'label' => false,
            static::OPTION_IMAGE_WIDTH => 150,
        ]);

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
            ->add(self::FIELD_FK_IMAGE_SET_ID, HiddenType::class, []);

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
            ->add(self::FIELD_IMAGE_PREVIEW, ImageType::class, [
                'required' => false,
                'label' => false,
                static::OPTION_IMAGE_WIDTH => 150,
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
            ->add(self::FIELD_IMAGE_SMALL, TextType::class, [
                'required' => true,
                'label' => 'Small Image URL',
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
            ->add(self::FIELD_IMAGE_LARGE, TextType::class, [
                'required' => true,
                'label' => 'Large Image URL',
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
    protected function addSortOrderField(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add(static::FIELD_SORT_ORDER, NumberType::class, [
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
