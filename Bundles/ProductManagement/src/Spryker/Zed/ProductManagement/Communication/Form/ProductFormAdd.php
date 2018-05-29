<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form;

use DateTime;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Shared\ProductManagement\ProductManagementConstants;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\ProductManagement\Communication\Form\Product\AttributeAbstractForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\AttributeSuperForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\GeneralForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\ImageSetForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\Price\ProductMoneyCollectionType;
use Spryker\Zed\ProductManagement\Communication\Form\Product\Price\ProductMoneyType;
use Spryker\Zed\ProductManagement\Communication\Form\Product\SeoForm;
use Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints\SkuRegex;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface getQueryContainer()
 */
class ProductFormAdd extends AbstractType
{
    const FIELD_SKU = 'sku';
    const FIELD_ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    const FIELD_PRICES = 'prices';
    const FIELD_TAX_RATE = 'tax_rate';
    const FIELD_NEW_FROM = 'new_from';
    const FIELD_NEW_TO = 'new_to';

    const FORM_ATTRIBUTE_ABSTRACT = 'attribute_abstract';
    const FORM_ATTRIBUTE_SUPER = 'attribute_super';
    const FORM_GENERAL = 'general';
    const FORM_PRICE_AND_TAX = 'price_and_tax';
    const FORM_PRICE_AND_STOCK = 'price_and_stock';
    const FORM_TAX_SET = 'tax_set';
    const FORM_SEO = 'seo';
    const FORM_STORE_RELATION = 'store_relation';
    const FORM_IMAGE_SET = 'image_set';

    const OPTION_ATTRIBUTE_ABSTRACT = 'option_attribute_abstract';
    const OPTION_ATTRIBUTE_SUPER = 'option_attribute_super';
    const OPTION_ID_LOCALE = 'option_id_locale';
    const OPTION_TAX_RATES = 'option_tax_rates';
    const OPTION_CURRENCY_ISO_CODE = 'currency_iso_code';

    const VALIDATION_GROUP_UNIQUE_SKU = 'validation_group_unique_sku';
    const VALIDATION_GROUP_ATTRIBUTE_ABSTRACT = 'validation_group_attribute_abstract';
    const VALIDATION_GROUP_ATTRIBUTE_SUPER = 'validation_group_attribute_super';
    const VALIDATION_GROUP_GENERAL = 'validation_group_general';
    const VALIDATION_GROUP_PRICE_AND_TAX = 'validation_group_price_and_tax';
    const VALIDATION_GROUP_PRICE_AND_STOCK = 'validation_group_price_and_stock';
    const VALIDATION_GROUP_SEO = 'validation_group_seo';
    const VALIDATION_GROUP_IMAGE_SET = 'validation_group_image';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $this->setRequired($resolver);
        $this->setDefaults($resolver);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    protected function setRequired(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            static::OPTION_ID_LOCALE,
            static::OPTION_ATTRIBUTE_ABSTRACT,
            static::OPTION_ATTRIBUTE_SUPER,
            static::OPTION_TAX_RATES,
        ]);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    protected function setDefaults(OptionsResolver $resolver)
    {
        $validationGroups = $this->getValidationGroups();

        $resolver->setDefaults([
            'constraints' => new Valid(),
            'required' => false,
            'validation_groups' => function () use ($validationGroups) {
                return $validationGroups;
            },
            'compound' => true,
            static::OPTION_CURRENCY_ISO_CODE => null,
        ]);
    }

    /**
     * @return array
     */
    protected function getValidationGroups()
    {
        return [
            Constraint::DEFAULT_GROUP,
            static::VALIDATION_GROUP_UNIQUE_SKU,
            static::VALIDATION_GROUP_GENERAL,
            static::VALIDATION_GROUP_PRICE_AND_TAX,
            static::VALIDATION_GROUP_PRICE_AND_STOCK,
            static::VALIDATION_GROUP_ATTRIBUTE_ABSTRACT,
            static::VALIDATION_GROUP_ATTRIBUTE_SUPER,
            static::VALIDATION_GROUP_SEO,
            static::VALIDATION_GROUP_IMAGE_SET,
        ];
    }

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
            ->addNewFromDateField($builder)
            ->addNewToDateField($builder)
            ->addProductAbstractIdHiddenField($builder)
            ->addGeneralLocalizedForms($builder)
            ->addAttributeSuperForm($builder, $options[self::OPTION_ATTRIBUTE_SUPER])
            ->addPriceForm($builder, $options)
            ->addTaxRateField($builder, $options)
            ->addSeoLocalizedForms($builder)
            ->addImageLocalizedForms($builder)
            ->addStoreRelationForm($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addGeneralLocalizedForms(FormBuilderInterface $builder)
    {
        $localeCollection = $this->getFactory()->createLocaleProvider()->getLocaleCollection();
        foreach ($localeCollection as $localeTransfer) {
            $name = self::getGeneralFormName($localeTransfer->getLocaleName());
            $this->addGeneralForm($builder, $name);
        }

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addSeoLocalizedForms(FormBuilderInterface $builder, array $options = [])
    {
        $localeCollection = $this->getFactory()->createLocaleProvider()->getLocaleCollection();
        foreach ($localeCollection as $localeTransfer) {
            $name = self::getSeoFormName($localeTransfer->getLocaleName());
            $this->addSeoForm($builder, $name, $options);
        }

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addAttributeAbstractForms(FormBuilderInterface $builder, array $options = [])
    {
        $localeCollection = $this->getFactory()->createLocaleProvider()->getLocaleCollection();
        foreach ($localeCollection as $localeTransfer) {
            $name = self::getAbstractAttributeFormName($localeTransfer->getLocaleName());
            $localeTransfer = $this->getFactory()->createLocaleProvider()->getLocaleTransfer($localeTransfer->getLocaleName());
            $this->addAttributeAbstractForm($builder, $name, $localeTransfer, $options[$localeTransfer->getLocaleName()]);
        }

        $defaultName = ProductFormAdd::getLocalizedPrefixName(
            ProductFormAdd::FORM_ATTRIBUTE_ABSTRACT,
            ProductManagementConstants::PRODUCT_MANAGEMENT_DEFAULT_LOCALE
        );

        $this->addAttributeAbstractForm(
            $builder,
            $defaultName,
            null,
            $options[ProductManagementConstants::PRODUCT_MANAGEMENT_DEFAULT_LOCALE]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addImageLocalizedForms(FormBuilderInterface $builder)
    {
        $localeCollection = $this->getFactory()->createLocaleProvider()->getLocaleCollection(true);
        foreach ($localeCollection as $localeTransfer) {
            $name = self::getImagesFormName($localeTransfer->getLocaleName());
            $this->addImageSetForm($builder, $name);
        }

        $defaultName = ProductFormAdd::getLocalizedPrefixName(
            ProductFormAdd::FORM_IMAGE_SET,
            ProductManagementConstants::PRODUCT_MANAGEMENT_DEFAULT_LOCALE
        );

        $this->addImageSetForm($builder, $defaultName);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addStoreRelationForm(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FORM_STORE_RELATION,
            $this->getFactory()->getStoreRelationFormTypePlugin()->getType()
        );

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
                'label' => 'SKU Prefix',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'groups' => [self::VALIDATION_GROUP_UNIQUE_SKU],
                    ]),
                    new SkuRegex([
                        'groups' => [self::VALIDATION_GROUP_UNIQUE_SKU],
                    ]),
                    new Callback([
                        'callback' => function ($sku, ExecutionContextInterface $context) {
                            $form = $context->getRoot();
                            $idProductAbstract = $form->get(ProductFormAdd::FIELD_ID_PRODUCT_ABSTRACT)->getData();
                            $sku = $this->getFactory()->getUtilTextService()->generateSlug($sku);

                            $skuCount = $this->getFactory()->getProductQueryContainer()
                                ->queryProduct()
                                ->filterByFkProductAbstract($idProductAbstract, Criteria::NOT_EQUAL)
                                ->filterBySku($sku)
                                ->_or()
                                ->useSpyProductAbstractQuery()
                                    ->filterBySku($sku)
                                ->endUse()
                                ->count();

                            if ($skuCount > 0) {
                                $context->addViolation(
                                    sprintf('The SKU "%s" is already used', $sku)
                                );
                            }
                        },
                        'groups' => [self::VALIDATION_GROUP_UNIQUE_SKU],
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
    protected function addNewFromDateField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_NEW_FROM, DateType::class, [
            'widget' => 'single_text',
            'required' => false,
            'attr' => [
                'class' => 'datepicker js-from-date safe-datetime',
            ],
            'constraints' => [
                $this->createNewFromRangeConstraint(),
            ],
        ]);

        $builder->get(static::FIELD_NEW_FROM)
            ->addModelTransformer($this->createDateTimeModelTransformer());

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNewToDateField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_NEW_TO, DateType::class, [
            'widget' => 'single_text',
            'required' => false,
            'attr' => [
                'class' => 'datepicker js-to-date safe-datetime',
            ],
            'constraints' => [
                $this->createNewToFieldRangeConstraint(),
            ],
        ]);

        $builder->get(static::FIELD_NEW_TO)
            ->addModelTransformer($this->createDateTimeModelTransformer());

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
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    protected function addGeneralForm(FormBuilderInterface $builder, $name, array $options = [])
    {
        $builder
            ->add($name, $this->createGeneralForm(), [
                'label' => false,
                'constraints' => [new Callback([
                    'callback' => function ($dataToValidate, ExecutionContextInterface $context) {
                        $selectedAttributes = array_filter(array_values($dataToValidate));
                        if (empty($selectedAttributes) && !array_key_exists($context->getGroup(), GeneralForm::$errorFieldsDisplayed)) {
                            $context->addViolation('Please enter at least Sku and Name of the product in every locale under General');
                            GeneralForm::$errorFieldsDisplayed[$context->getGroup()] = true;
                        }
                    },
                    'groups' => [self::VALIDATION_GROUP_GENERAL],
                ])],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string $name
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     * @param array $options
     *
     * @return $this
     */
    protected function addAttributeAbstractForm(FormBuilderInterface $builder, $name, ?LocaleTransfer $localeTransfer = null, array $options = [])
    {
        $builder
            ->add($name, CollectionType::class, [
                'entry_type' => AttributeAbstractForm::class,
                'entry_options' => [
                    AttributeAbstractForm::OPTION_ATTRIBUTE => $options,
                    AttributeAbstractForm::OPTION_LOCALE_TRANSFER => $localeTransfer,
                ],
                'label' => false,
                'constraints' => [new Callback([
                    'callback' => function ($attributes, ExecutionContextInterface $context) {
                        foreach ($attributes as $type => $valueSet) {
                            if ($valueSet[AttributeAbstractForm::FIELD_NAME] && empty($valueSet[AttributeAbstractForm::FIELD_VALUE])) {
                                $context->addViolation(sprintf(
                                    'Please enter value for product attribute "%s" or disable it',
                                    $type
                                ));
                            }
                        }
                    },
                    'groups' => [self::VALIDATION_GROUP_ATTRIBUTE_ABSTRACT],
                ])],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addAttributeSuperForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add(self::FORM_ATTRIBUTE_SUPER, CollectionType::class, [
                'entry_type' => AttributeSuperForm::class,
                'entry_options' => [
                    AttributeSuperForm::OPTION_ATTRIBUTE => $options,
                ],
                'label' => false,
                'constraints' => [new Callback([
                    'callback' => function ($attributes, ExecutionContextInterface $context) {
                        foreach ($attributes as $type => $valueSet) {
                            if ($valueSet[AttributeSuperForm::FIELD_NAME] && empty($valueSet[AttributeSuperForm::FIELD_VALUE])) {
                                $context->addViolation(sprintf(
                                    'Please enter value for variant attribute "%s" or disable it',
                                    $type
                                ));
                            }
                        }
                    },
                    'groups' => [self::VALIDATION_GROUP_ATTRIBUTE_SUPER],
                ])],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addPriceForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add(
            static::FIELD_PRICES,
            ProductMoneyCollectionType::class,
            [
                'entry_options' => [
                    'data_class' => PriceProductTransfer::class,
                ],
                'entry_type' => ProductMoneyType::class,
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addTaxRateField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_TAX_RATE, Select2ComboBoxType::class, [
            'label' => 'Tax Set',
            'required' => true,
            'choices' => array_flip($options[static::OPTION_TAX_RATES]),
            'choices_as_values' => true,
            'placeholder' => '-',
            'constraints' => [
                new NotBlank(),
            ],
        ]);
        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string $name
     *
     * @return $this
     */
    protected function addImageSetForm(FormBuilderInterface $builder, $name)
    {
        $builder
            ->add($name, CollectionType::class, [
                'entry_type' => ImageSetForm::class,
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'prototype_name' => '__image_set_name__',
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    protected function addSeoForm(FormBuilderInterface $builder, $name, array $options = [])
    {
        $builder
            ->add($name, SeoForm::class, [
                'label' => false,
            ]);

        return $this;
    }

    /**
     * @param string $prefix
     * @param string $localeCode
     *
     * @return string
     */
    public static function getLocalizedPrefixName($prefix, $localeCode)
    {
        return $prefix . '_' . $localeCode . '';
    }

    /**
     * @param string $localeCode
     *
     * @return string
     */
    public static function getGeneralFormName($localeCode)
    {
        return self::getLocalizedPrefixName(self::FORM_GENERAL, $localeCode);
    }

    /**
     * @param string $localeCode
     *
     * @return string
     */
    public static function getSeoFormName($localeCode)
    {
        return self::getLocalizedPrefixName(self::FORM_SEO, $localeCode);
    }

    /**
     * @param string $localeCode
     *
     * @return string
     */
    public static function getAbstractAttributeFormName($localeCode)
    {
        return self::getLocalizedPrefixName(self::FORM_ATTRIBUTE_ABSTRACT, $localeCode);
    }

    /**
     * @param string $localeCode
     *
     * @return string
     */
    public static function getImagesFormName($localeCode)
    {
        return self::getLocalizedPrefixName(self::FORM_IMAGE_SET, $localeCode);
    }

    /**
     * @return string
     */
    protected function createGeneralForm()
    {
        return GeneralForm::class;
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function createNewFromRangeConstraint()
    {
        return new Callback([
            'callback' => function ($newFrom, ExecutionContextInterface $context) {
                $formData = $context->getRoot()->getData();
                if (!$newFrom) {
                    return;
                }

                if ($formData[static::FIELD_NEW_TO]) {
                    if ($newFrom > $formData[static::FIELD_NEW_TO]) {
                        $context->addViolation('Date "New from" cannot be later than "New to".');
                    }

                    if ($newFrom == $formData[static::FIELD_NEW_TO]) {
                        $context->addViolation('Date "New from" is the same as "New to".');
                    }
                }
            },
        ]);
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function createNewToFieldRangeConstraint()
    {
        return new Callback([
            'callback' => function ($newTo, ExecutionContextInterface $context) {
                $formData = $context->getRoot()->getData();
                if (!$newTo) {
                    return;
                }

                if ($formData[static::FIELD_NEW_FROM]) {
                    if ($newTo < $formData[static::FIELD_NEW_FROM]) {
                        $context->addViolation('Date "New to" cannot be earlier than "New from".');
                    }
                }
            },
        ]);
    }

    /**
     * @return \Symfony\Component\Form\CallbackTransformer
     */
    protected function createDateTimeModelTransformer()
    {
        return new CallbackTransformer(
            function ($value) {
                if ($value !== null) {
                    $value = new DateTime($value);
                }
                return $value;
            },
            function ($value) {
                if ($value instanceof DateTime) {
                    $value = $value->format('Y-m-d H:i:s');
                }
                return $value;
            }
        );
    }
}
