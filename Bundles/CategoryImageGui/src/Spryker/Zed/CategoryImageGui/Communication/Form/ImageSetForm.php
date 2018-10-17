<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageGui\Communication\Form;

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

class ImageSetForm extends AbstractType
{
    public const FIELD_SET_ID = 'id_category_image_set';
    public const FIELD_SET_NAME = 'name';
    public const FIELD_SET_FK_LOCALE = 'fk_locale';
    public const FIELD_SET_FK_CATEGORY = 'fk_category';

    public const CATEGORY_IMAGES = 'category_images';

    public const VALIDATION_GROUP_IMAGE_COLLECTION = 'validation_group_image_collection';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'required' => false,
        ]);

        $validationGroups = [
            Constraint::DEFAULT_GROUP,
            self::VALIDATION_GROUP_IMAGE_COLLECTION,
        ];

        $resolver->setDefaults([
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

        $this
            ->addSetIdField($builder)
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
        $builder
            ->add(self::FIELD_SET_ID, HiddenType::class, []);

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
            ->add(self::FIELD_SET_NAME, TextType::class, [
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
        $builder
            ->add(self::FIELD_SET_FK_LOCALE, HiddenType::class, []);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCategoryHiddenField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_SET_FK_CATEGORY, HiddenType::class, []);

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
            ->add(self::CATEGORY_IMAGES, CollectionType::class, [
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
                    'groups' => [self::VALIDATION_GROUP_IMAGE_COLLECTION],
                ])],
            ]);

        return $this;
    }
}
