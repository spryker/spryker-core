<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Communication\Form;

use ArrayObject;
use Spryker\Zed\ProductOption\Communication\Form\Constraint\UniqueGroupName;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ProductOptionGroupForm extends AbstractType
{

    const FIELD_TAX_SET_FIELD = 'fkTaxSet';
    const FIELD_VALUES = 'productOptionValues';
    const FIELD_ID_PRODUCT_OPTION_GROUP = 'idProductOptionGroup';
    const FIELD_NAME = 'name';
    const FIELD_VALUE_TRANSLATIONS = 'productOptionValueTranslations';
    const FIELD_GROUP_NAME_TRANSLATIONS = 'groupNameTranslations';

    const OPTION_TAX_SETS = 'optionTaxSets';

    const PRODUCTS_TO_BE_ASSIGNED = 'products_to_be_assigned';
    const PRODUCTS_TO_BE_DE_ASSIGNED = 'products_to_be_de_assigned';
    const PRODUCT_OPTION_VALUES_TO_BE_REMOVED = 'product_option_values_to_be_removed';

    const ALPHA_NUMERIC_PATTERN = '/^[a-z0-9\.\_]+$/';

    /**
     * @var \Spryker\Zed\ProductOption\Communication\Form\ProductOptionValueForm
     */
    protected $productOptionForm;

    /**
     * @var \Spryker\Zed\ProductOption\Communication\Form\ProductOptionTranslationForm
     */
    protected $productOptionTranslationForm;

    /**
     * @var \Symfony\Component\Form\DataTransformerInterface
     */
    protected $arrayToArrayObjectTransformer;

    /**
     * @var \Symfony\Component\Form\DataTransformerInterface
     */
    protected $stringToArrayTransformer;

    /**
     * @var \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface
     */
    protected $productOptionQueryContainer;

    /**
     * @param \Spryker\Zed\ProductOption\Communication\Form\ProductOptionValueForm $productOptionForm
     * @param \Spryker\Zed\ProductOption\Communication\Form\ProductOptionTranslationForm $productOptionTranslationForm
     * @param \Symfony\Component\Form\DataTransformerInterface $arrayToArrayObjectTransformer
     * @param \Symfony\Component\Form\DataTransformerInterface $stringToArrayTransformer
     * @param \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface $productOptionQueryContainer
     */
    public function __construct(
        ProductOptionValueForm $productOptionForm,
        ProductOptionTranslationForm $productOptionTranslationForm,
        DataTransformerInterface $arrayToArrayObjectTransformer,
        DataTransformerInterface $stringToArrayTransformer,
        ProductOptionQueryContainerInterface $productOptionQueryContainer
    ) {
        $this->productOptionForm = $productOptionForm;
        $this->productOptionTranslationForm = $productOptionTranslationForm;
        $this->arrayToArrayObjectTransformer = $arrayToArrayObjectTransformer;
        $this->stringToArrayTransformer = $stringToArrayTransformer;
        $this->productOptionQueryContainer = $productOptionQueryContainer;
    }

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
        $builder->add(self::FIELD_NAME, 'text', [
            'label' => 'Group name translation key',
            'required' => true,
            'constraints' => [
                new NotBlank(),
                new UniqueGroupName([
                    UniqueGroupName::OPTION_PRODUCT_OPTION_QUERY_CONTAINER => $this->productOptionQueryContainer,
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
        $builder->add(self::FIELD_VALUES, 'collection', [
            'type' => $this->productOptionForm,
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
            ->addModelTransformer($this->arrayToArrayObjectTransformer);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValueTranslationFields(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_VALUE_TRANSLATIONS, 'collection', [
            'type' => $this->productOptionTranslationForm,
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
        ]);

        $builder->get(self::FIELD_VALUE_TRANSLATIONS)
            ->addModelTransformer($this->arrayToArrayObjectTransformer);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addGroupNameTranslationFields(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_GROUP_NAME_TRANSLATIONS, 'collection', [
            'type' => $this->productOptionTranslationForm,
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
        ]);

        $builder->get(self::FIELD_GROUP_NAME_TRANSLATIONS)
            ->addModelTransformer($this->arrayToArrayObjectTransformer);

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
            'choice',
            [
                'label' => 'Tax set',
                'choices' => $options[self::OPTION_TAX_SETS],
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
        $builder->add(self::FIELD_ID_PRODUCT_OPTION_GROUP, 'hidden');

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
            ->add(self::PRODUCTS_TO_BE_ASSIGNED, 'hidden', [
                'attr' => [
                    'id' => self::PRODUCTS_TO_BE_ASSIGNED,
                ],
            ]);

        $builder->get(self::PRODUCTS_TO_BE_ASSIGNED)
            ->addModelTransformer($this->stringToArrayTransformer);

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
            ->add(self::PRODUCTS_TO_BE_DE_ASSIGNED, 'hidden', [
                'attr' => [
                    'id' => self::PRODUCTS_TO_BE_DE_ASSIGNED,
                ],
            ]);

        $builder->get(self::PRODUCTS_TO_BE_DE_ASSIGNED)
            ->addModelTransformer($this->stringToArrayTransformer);

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
            ->add(self::PRODUCT_OPTION_VALUES_TO_BE_REMOVED, 'hidden', [
                'attr' => [
                    'id' => self::PRODUCT_OPTION_VALUES_TO_BE_REMOVED,
                ],
            ]);

        $builder->get(self::PRODUCT_OPTION_VALUES_TO_BE_REMOVED)
            ->addModelTransformer($this->stringToArrayTransformer);

        return $this;
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'product_option_general';
    }

}
