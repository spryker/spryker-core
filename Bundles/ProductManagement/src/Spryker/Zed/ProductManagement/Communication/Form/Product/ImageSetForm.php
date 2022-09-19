<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Product;

use Spryker\Zed\ProductManagement\Communication\Form\AbstractSubForm;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductManagement\ProductManagementConfig getConfig()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementRepositoryInterface getRepository()
 */
class ImageSetForm extends AbstractSubForm
{
    /**
     * @var string
     */
    public const FIELD_SET_ID = 'id_product_image_set';

    /**
     * @var string
     */
    public const FIELD_SET_NAME = 'name';

    /**
     * @var string
     */
    public const FIELD_SET_FK_LOCALE = 'fk_locale';

    /**
     * @var string
     */
    public const FIELD_SET_FK_PRODUCT = 'fk_product';

    /**
     * @var string
     */
    public const FIELD_SET_FK_PRODUCT_ABSTRACT = 'fk_product_abstract';

    /**
     * @var string
     */
    public const PRODUCT_IMAGES = 'product_images';

    /**
     * @var string
     */
    public const VALIDATION_GROUP_IMAGE_COLLECTION = 'validation_group_image_collection';

    /**
     * @var string
     */
    protected const OPTION_LOCALE = 'locale';

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
            'constraints' => new Valid(),
            'required' => false,
            'validation_groups' => function (FormInterface $form) use ($validationGroups) {
                return $validationGroups;
            },
            'compound' => true,
            static::OPTION_LOCALE => null,
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'product_image_set';
    }

    /**
     * @deprecated Use {@link getBlockPrefix()} instead.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
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
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addSetIdField(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add(static::FIELD_SET_ID, HiddenType::class, []);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add(static::FIELD_SET_NAME, TextType::class, [
                'required' => false,
                'label' => 'Image Set Name',
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addLocaleHiddenField(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add(static::FIELD_SET_FK_LOCALE, HiddenType::class, []);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addProductHiddenField(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add(static::FIELD_SET_FK_PRODUCT, HiddenType::class, []);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addProductAbstractHiddenField(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add(static::FIELD_SET_FK_PRODUCT_ABSTRACT, HiddenType::class, []);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addImageCollectionForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add(static::PRODUCT_IMAGES, CollectionType::class, [
                'entry_type' => ImageCollectionForm::class,
                'entry_options' => [
                    'locale' => $options[static::OPTION_LOCALE],
                ],
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

                        if ($selectedAttributes) {
                            $context->addViolation('Please enter required image information');
                        }
                    },
                    'groups' => [static::VALIDATION_GROUP_IMAGE_COLLECTION],
                ])],
            ]);

        return $this;
    }
}
