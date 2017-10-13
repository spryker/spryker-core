<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Product;

use Spryker\Zed\ProductManagement\Communication\Form\AbstractSubForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ImageSetForm extends AbstractSubForm
{
    const FIELD_SET_ID = 'id_product_image_set';
    const FIELD_SET_NAME = 'name';
    const FIELD_SET_FK_LOCALE = 'fk_locale';
    const FIELD_SET_FK_PRODUCT = 'fk_product';
    const FIELD_SET_FK_PRODUCT_ABSTRACT = 'fk_product_abstract';

    const PRODUCT_IMAGES = 'product_images';

    const VALIDATION_GROUP_IMAGE_COLLECTION = 'validation_group_image_collection';

    /**
     * @return string
     */
    public function getName()
    {
        return 'product_image_set';
    }

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
            self::VALIDATION_GROUP_IMAGE_COLLECTION,
        ];

        $resolver->setDefaults([
            'cascade_validation' => true,
            'required' => false,
            'validation_groups' => function (FormInterface $form) use ($validationGroups) {
                return $validationGroups;
            },
            'compound' => true,
        ]);
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
            ->addSetIdField($builder, $options)
            ->addNameField($builder, $options)
            ->addLocaleHiddenField($builder, $options)
            ->addProductHiddenField($builder, $options)
            ->addProductAbstractHiddenField($builder, $options)
            ->addImageCollectionForm($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addSetIdField(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add(self::FIELD_SET_ID, 'hidden', []);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add(self::FIELD_SET_NAME, 'text', [
                'required' => false,
                'label' => 'Image Set Name',
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addLocaleHiddenField(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add(self::FIELD_SET_FK_LOCALE, 'hidden', []);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addProductHiddenField(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add(self::FIELD_SET_FK_PRODUCT, 'hidden', []);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addProductAbstractHiddenField(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add(self::FIELD_SET_FK_PRODUCT_ABSTRACT, 'hidden', []);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addImageCollectionForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add(self::PRODUCT_IMAGES, 'collection', [
                'type' => new ImageCollectionForm(),
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'constraints' => [new Callback([
                    'methods' => [
                        function ($images, ExecutionContextInterface $context) {
                            $selectedAttributes = [];
                            foreach ($images as $type => $valueSet) {
                                if (!empty($valueSet['value'])) {
                                    $selectedAttributes[] = $valueSet['value'];
                                    break;
                                }
                            }

                            if (!empty($selectedAttributes)) {
                                $context->addViolation('Please enter required image information');
                            }
                        },
                    ],
                    'groups' => [self::VALIDATION_GROUP_IMAGE_COLLECTION],
                ])],
            ]);

        return $this;
    }
}
