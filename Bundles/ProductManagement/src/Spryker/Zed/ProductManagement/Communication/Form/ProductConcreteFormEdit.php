<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form;

use DateTime;
use Spryker\Zed\ProductManagement\Communication\Form\Product\Concrete\ConcreteGeneralForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\Concrete\StockForm;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductManagement\ProductManagementConfig getConfig()
 */
class ProductConcreteFormEdit extends ProductFormAdd
{
    public const FIELD_ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    public const FIELD_ID_PRODUCT_CONCRETE = 'id_product';
    public const FIELD_VALID_FROM = 'valid_from';
    public const FIELD_VALID_TO = 'valid_to';

    public const FORM_ASSIGNED_BUNDLED_PRODUCTS = 'assigned_bundled_products';
    public const BUNDLED_PRODUCTS_TO_BE_REMOVED = 'product_bundles_to_be_removed';
    public const FORM_PRODUCT_CONCRETE_SUPER_ATTRIBUTES = 'form_product_concrete_super_attributes';

    public const OPTION_IS_BUNDLE_ITEM = 'is_bundle_item';
    public const VALIDITY_DATETIME_FORMAT = 'yyyy-MM-dd H:mm:ss';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addSkuField($builder)
            ->addSuperAttributesForm($builder, $options)
            ->addValidFromField($builder)
            ->addValidToField($builder)
            ->addProductAbstractIdHiddenField($builder)
            ->addProductConcreteIdHiddenField($builder)
            ->addGeneralLocalizedForms($builder)
            ->addPriceForm($builder, $options)
            ->addStockForm($builder, $options)
            ->addImageLocalizedForms($builder)
            ->addAssignBundledProductForm($builder, $options)
            ->addBundledProductsToBeRemoved($builder)
            ->addFormBuildPlugins($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addBundledProductsToBeRemoved(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::BUNDLED_PRODUCTS_TO_BE_REMOVED, HiddenType::class, [
                'attr' => [
                    'id' => self::BUNDLED_PRODUCTS_TO_BE_REMOVED,
                ],
            ]);

        $builder->get(self::BUNDLED_PRODUCTS_TO_BE_REMOVED)
            ->addModelTransformer(new CallbackTransformer(
                function ($value) {
                    if ($value) {
                        return implode(',', $value);
                    }
                },
                function ($bundledProductsToBeRemoved) {
                    if (!$bundledProductsToBeRemoved) {
                        return [];
                    }

                    return explode(',', $bundledProductsToBeRemoved);
                }
            ));

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSkuField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_SKU, TextType::class, [
                'label' => 'SKU',
                'attr' => [
                    'readonly' => 'readonly',
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValidFromField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_VALID_FROM,
            DateTimeType::class,
            [
                'format' => static::VALIDITY_DATETIME_FORMAT,
                'label' => 'Valid From (GMT)',
                'widget' => 'single_text',
                'required' => false,
                'attr' => [
                    'class' => 'datepicker js-from-datetime safe-datetime',
                ],
                'constraints' => [
                    new Callback([
                        'callback' => function ($newFrom, ExecutionContextInterface $context) {
                            $formData = $context->getRoot()->getData();

                            if (!$newFrom) {
                                return;
                            }

                            if (empty($formData[static::FIELD_VALID_TO])) {
                                return;
                            }

                            $newValidFromDateTime = new DateTime($newFrom);
                            $validToDateTime = new DateTime($formData[static::FIELD_VALID_TO]);

                            if ($newValidFromDateTime > $validToDateTime) {
                                $context->addViolation('Date "Valid from" can not be later than "Valid to".');
                            }

                            if ($newValidFromDateTime->format('c') === $validToDateTime->format('c')) {
                                $context->addViolation('Date "Valid from" can not be the same as "Valid to".');
                            }
                        },
                    ]),
                ],
            ]
        );

        $this->addDateTimeTransformer(static::FIELD_VALID_FROM, $builder);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValidToField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_VALID_TO,
            DateTimeType::class,
            [
                'format' => static::VALIDITY_DATETIME_FORMAT,
                'label' => 'Valid To (GMT)',
                'widget' => 'single_text',
                'required' => false,
                'attr' => [
                    'class' => 'datepicker js-to-datetime safe-datetime',
                ],
                'constraints' => [
                    new Callback([
                        'callback' => function ($newTo, ExecutionContextInterface $context) {
                            $formData = $context->getRoot()->getData();

                            if (!$newTo) {
                                return;
                            }

                            if (empty($formData[static::FIELD_VALID_FROM])) {
                                return;
                            }

                            $newValidToDateTime = new DateTime($newTo);
                            $validFromDateTime = new DateTime($formData[static::FIELD_VALID_FROM]);

                            if ($newValidToDateTime < $validFromDateTime) {
                                $context->addViolation('Date "Valid to" can not be earlier than "Valid from".');
                            }
                        },
                    ]),
                ],
            ]
        );

        $this->addDateTimeTransformer(static::FIELD_VALID_TO, $builder);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductAbstractIdHiddenField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_ID_PRODUCT_ABSTRACT, HiddenType::class, []);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductConcreteIdHiddenField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_ID_PRODUCT_CONCRETE, HiddenType::class, []);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addAssignBundledProductForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FORM_ASSIGNED_BUNDLED_PRODUCTS, CollectionType::class, [
            'entry_type' => BundledProductForm::class,
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addStockForm(FormBuilderInterface $builder, array $options = [])
    {
        if (isset($options[static::OPTION_IS_BUNDLE_ITEM]) && $options[static::OPTION_IS_BUNDLE_ITEM] === true) {
            return $this;
        }

        $builder
            ->add(self::FORM_PRICE_AND_STOCK, CollectionType::class, [
                'entry_type' => StockForm::class,
                'label' => false,
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addSuperAttributesForm(FormBuilderInterface $builder, array $options)
    {
        $superAttributes = $this->getProductConcreteSuperAttributes($builder->getData()[static::FIELD_ID_PRODUCT_CONCRETE]);

        if (empty($superAttributes)) {
            return $this;
        }

        $builder->add(
            static::FORM_PRODUCT_CONCRETE_SUPER_ATTRIBUTES,
            FormType::class,
            [
                'compound' => true,
                'label' => 'Super attributes',
            ]
        );

        foreach ($superAttributes as $attributeKey => $attributeValue) {
            $builder->get(static::FORM_PRODUCT_CONCRETE_SUPER_ATTRIBUTES)
                ->add(
                    $attributeKey,
                    TextType::class,
                    [
                        'label' => $attributeKey,
                        'data' => $attributeValue,
                        'attr' => [
                            'readonly' => true,
                        ],
                    ]
                );
        }

        return $this;
    }

    /**
     * @return string
     */
    protected function createGeneralForm()
    {
        return ConcreteGeneralForm::class;
    }

    /**
     * @param string $fieldName
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addDateTimeTransformer($fieldName, FormBuilderInterface $builder)
    {
        $timeFormat = $this->getConfig()->getValidityTimeFormat();

        $builder
            ->get($fieldName)
            ->addModelTransformer(new CallbackTransformer(
                function ($dateAsString) {
                    if (!$dateAsString) {
                        return null;
                    }

                    return new DateTime($dateAsString);
                },
                function ($dateAsObject) use ($timeFormat) {
                    /** @var \DateTime|null $dateAsObject */
                    if (!$dateAsObject) {
                        return null;
                    }

                    return $dateAsObject->format($timeFormat);
                }
            ));
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(self::OPTION_IS_BUNDLE_ITEM);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addFormBuildPlugins(FormBuilderInterface $builder, array $options): self
    {
        /** @var \Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductConcreteEditFormExpanderPluginInterface $plugin */
        foreach ($this->getFactory()->getProductConcreteEditFormExpanderPlugins() as $plugin) {
            $plugin->buildForm($builder, $options);
        }

        return $this;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return array
     */
    protected function getProductConcreteSuperAttributes(int $idProductConcrete)
    {
        $superAttributes = [];

        $productConcreteTransfer = $this->getFactory()->getProductFacade()->findProductConcreteById($idProductConcrete);
        $productConcreteAttributes = $productConcreteTransfer->getAttributes();
        $superAttributesTransfers = $this->getFactory()->getProductAttributeFacade()->getUniqueSuperAttributesFromConcreteProducts([$productConcreteTransfer]);

        foreach ($superAttributesTransfers as $productManagementAttributeTransfer) {
            $attributeKey = $productManagementAttributeTransfer->getKey();

            if ($productConcreteAttributes[$attributeKey] !== null) {
                $superAttributes[$attributeKey] = $productConcreteAttributes[$attributeKey];
            }
        }

        return $superAttributes;
    }
}
