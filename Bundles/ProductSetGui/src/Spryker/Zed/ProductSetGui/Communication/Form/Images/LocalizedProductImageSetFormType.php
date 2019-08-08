<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Communication\Form\Images;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @method \Spryker\Zed\ProductSetGui\Communication\ProductSetGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductSetGui\Persistence\ProductSetGuiQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductSetGui\ProductSetGuiConfig getConfig()
 */
class LocalizedProductImageSetFormType extends AbstractType
{
    public const FIELD_ID_PRODUCT_IMAGE_SET = 'id_product_image_set';
    public const FIELD_NAME = 'name';
    public const FIELD_FK_LOCALE = 'fk_locale';
    public const FIELD_FK_RESOURCE_PRODUCT_SET = 'fk_resource_product_set';
    public const FIELD_PRODUCT_IMAGE_COLLECTION = 'product_image_collection';

    public const VALIDATION_GROUP_IMAGE_COLLECTION = 'validation_group_image_collection';

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'product_image_set';
    }

    /**
     * @deprecated Use `getBlockPrefix()` instead.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options = [])
    {
        parent::buildForm($builder, $options);

        $this
            ->addIdProductImageSet($builder)
            ->addNameField($builder)
            ->addFkLocaleField($builder)
            ->addFkResourceProductSetField($builder)
            ->addProductImageCollectionForm($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdProductImageSet(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_PRODUCT_IMAGE_SET, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_NAME, TextType::class, [
            'required' => true,
            'label' => 'Image Set Name',
            'constraints' => [
                new NotBlank(),
                new Regex(
                    [
                        'pattern' => '/^[A-Za-z0-9_-]+$/',
                        'match' => true,
                        'message' => 'Please enter name using only letters, numbers, underscore or dash.',
                    ]
                ),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFkLocaleField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FK_LOCALE, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFkResourceProductSetField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FK_RESOURCE_PRODUCT_SET, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductImageCollectionForm(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::FIELD_PRODUCT_IMAGE_COLLECTION, CollectionType::class, [
                'entry_type' => ProductImageFormType::class,
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
            ]);

        return $this;
    }
}
