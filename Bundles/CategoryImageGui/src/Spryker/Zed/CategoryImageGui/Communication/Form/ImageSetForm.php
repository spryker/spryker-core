<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageGui\Communication\Form;

use Generated\Shared\Transfer\CategoryImageSetTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\CategoryImageGui\Communication\CategoryImageGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CategoryImageGui\CategoryImageGuiConfig getConfig()
 */
class ImageSetForm extends AbstractType
{
    public const FIELD_ID = 'idCategoryImageSet';
    public const FIELD_NAME = 'name';
    public const FIELD_LOCALE = 'locale';
    public const FIELD_CATEGORY = 'idCategory';
    public const CATEGORY_IMAGES = 'categoryImages';

    public const VALIDATION_GROUP_IMAGE_COLLECTION = 'validation_group_image_collection';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $validationGroups = [
            Constraint::DEFAULT_GROUP,
            static::VALIDATION_GROUP_IMAGE_COLLECTION,
        ];

        $resolver->setDefaults([
            'data_class' => CategoryImageSetTransfer::class,
            'constraints' => new Valid(),
            'required' => false,
            'validation_groups' => function () use ($validationGroups) {
                return $validationGroups;
            },
            'compound' => true,
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'category_image_set';
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

        $this->addSetIdField($builder)
            ->addNameField($builder)
            ->addLocaleHiddenField($builder)
            ->addCategoryHiddenField($builder)
            ->addImageCollectionForm($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSetIdField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID, HiddenType::class, []);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::FIELD_NAME, TextType::class, [
                'required' => false,
                'label' => 'Image Set Name',
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addLocaleHiddenField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_LOCALE, HiddenType::class);
        $builder->get(static::FIELD_LOCALE)
            ->addModelTransformer(
                $this->getFactory()->createLocaleTransformer()
            );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCategoryHiddenField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CATEGORY, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addImageCollectionForm(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::CATEGORY_IMAGES, CollectionType::class, [
                'entry_type' => ImageCollectionForm::class,
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'constraints' => [new Callback([
                    'callback' => function ($images, ExecutionContextInterface $context) {
                        $selectedAttributes = [];
                        foreach ($images as $valueSet) {
                            if (!empty($valueSet['value'])) {
                                $selectedAttributes[] = $valueSet['value'];
                                break;
                            }
                        }

                        if (!empty($selectedAttributes)) {
                            $context->addViolation('Please enter required image information');
                        }
                    },
                    'groups' => [static::VALIDATION_GROUP_IMAGE_COLLECTION],
                ])],
            ]);

        $builder->get(static::CATEGORY_IMAGES)->addModelTransformer(
            $this->getFactory()->createImageCollectionTransformer()
        );

        return $this;
    }
}
