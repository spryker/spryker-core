<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Communication\Form;

use ArrayObject;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\ProductOption\Communication\Form\Constraint\UniqueGroupName;
use Spryker\Zed\ProductOption\ProductOptionConfig;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\ProductOption\Business\ProductOptionFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOption\Communication\ProductOptionCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface getQueryContainer()
 */
class ProductOptionGroupForm extends AbstractType
{
    public const FIELD_TAX_SET_FIELD = 'fkTaxSet';
    public const FIELD_VALUES = 'productOptionValues';
    public const FIELD_ID_PRODUCT_OPTION_GROUP = 'idProductOptionGroup';
    public const FIELD_NAME = 'name';
    public const FIELD_VALUE_TRANSLATIONS = 'productOptionValueTranslations';
    public const FIELD_GROUP_NAME_TRANSLATIONS = 'groupNameTranslations';

    public const OPTION_TAX_SETS = 'optionTaxSets';

    public const PRODUCTS_TO_BE_ASSIGNED = 'products_to_be_assigned';
    public const PRODUCTS_TO_BE_DE_ASSIGNED = 'products_to_be_de_assigned';
    public const PRODUCT_OPTION_VALUES_TO_BE_REMOVED = 'product_option_values_to_be_removed';

    public const ALPHA_NUMERIC_PATTERN = '/^[a-z0-9\.\_]+$/';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string[] $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addNameField($builder)
            ->addValuesFields($builder)
            ->addValueTranslationFields($builder)
            ->addGroupNameTranslationFields($builder)
            ->addTaxSetField($builder, $options)
            ->addIdProductOptionGroup($builder)
            ->addProductsToBeAssignedField($builder)
            ->addProductsToBeDeAssignedField($builder)
            ->addProductOptionValuesToBeRemoved($builder);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(self::OPTION_TAX_SETS);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_NAME, TextType::class, [
            'label' => 'Group name translation key',
            'required' => true,
            'attr' => [
                'placeholder' => ProductOptionConfig::PRODUCT_OPTION_GROUP_NAME_TRANSLATION_PREFIX . '(your key)',
            ],
            'constraints' => [
                new NotBlank(),
                new UniqueGroupName([
                    UniqueGroupName::OPTION_PRODUCT_OPTION_QUERY_CONTAINER => $this->getQueryContainer(),
                ]),
                new Regex([
                    'pattern' => self::ALPHA_NUMERIC_PATTERN,
                    'message' => 'Invalid key provided. Valid values "a-z", "0-9", ".", "_".',
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
    protected function addValuesFields(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_VALUES, CollectionType::class, [
            'entry_type' => ProductOptionValueForm::class,
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
            'constraints' => [
                new Callback([
                    'callback' => function (ArrayObject $values, ExecutionContextInterface $context) {
                        if (count($values) === 0) {
                            $context->buildViolation('No option values added.')
                                ->addViolation();
                        }
                    },
                ]),
            ],
        ]);

        $builder->get(self::FIELD_VALUES)
            ->addModelTransformer($this->getFactory()->createArrayToArrayObjectTransformer());

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValueTranslationFields(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_VALUE_TRANSLATIONS, CollectionType::class, [
            'entry_type' => ProductOptionTranslationForm::class,
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
        ]);

        $builder->get(self::FIELD_VALUE_TRANSLATIONS)
            ->addModelTransformer($this->getFactory()->createArrayToArrayObjectTransformer());

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addGroupNameTranslationFields(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_GROUP_NAME_TRANSLATIONS, CollectionType::class, [
            'entry_type' => ProductOptionTranslationForm::class,
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
        ]);

        $builder->get(self::FIELD_GROUP_NAME_TRANSLATIONS)
            ->addModelTransformer($this->getFactory()->createArrayToArrayObjectTransformer());

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addTaxSetField(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add(
            self::FIELD_TAX_SET_FIELD,
            ChoiceType::class,
            [
                'label' => 'Tax set',
                'choices' => array_flip($options[self::OPTION_TAX_SETS]),
                'choices_as_values' => true,
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdProductOptionGroup(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_ID_PRODUCT_OPTION_GROUP, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductsToBeAssignedField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::PRODUCTS_TO_BE_ASSIGNED, HiddenType::class, [
                'attr' => [
                    'id' => self::PRODUCTS_TO_BE_ASSIGNED,
                ],
            ]);

        $builder->get(self::PRODUCTS_TO_BE_ASSIGNED)
            ->addModelTransformer($this->getFactory()->createStringToArrayTransformer());

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductsToBeDeAssignedField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::PRODUCTS_TO_BE_DE_ASSIGNED, HiddenType::class, [
                'attr' => [
                    'id' => self::PRODUCTS_TO_BE_DE_ASSIGNED,
                ],
            ]);

        $builder->get(self::PRODUCTS_TO_BE_DE_ASSIGNED)
            ->addModelTransformer($this->getFactory()->createStringToArrayTransformer());

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductOptionValuesToBeRemoved(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::PRODUCT_OPTION_VALUES_TO_BE_REMOVED, HiddenType::class, [
                'attr' => [
                    'id' => self::PRODUCT_OPTION_VALUES_TO_BE_REMOVED,
                ],
            ]);

        $builder->get(self::PRODUCT_OPTION_VALUES_TO_BE_REMOVED)
            ->addModelTransformer($this->getFactory()->createStringToArrayTransformer());

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'product_option_general';
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
}
