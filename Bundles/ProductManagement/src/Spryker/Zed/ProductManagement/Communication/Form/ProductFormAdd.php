<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form;

use DateTime;
use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Shared\ProductManagement\ProductManagementConstants;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Spryker\Zed\ProductManagement\Communication\Form\DataProvider\LocaleProvider;
use Spryker\Zed\ProductManagement\Communication\Form\Product\AttributeAbstractForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\AttributeSuperForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\GeneralForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\ImageSetForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\PriceForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\SeoForm;
use Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints\SkuRegex;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToCurrencyInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToMoneyInterface;
use Spryker\Zed\ProductManagement\Dependency\Service\ProductManagementToUtilTextInterface;
use Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ProductFormAdd extends AbstractType
{
    const FIELD_SKU = 'sku';
    const FIELD_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    const FORM_ATTRIBUTE_ABSTRACT = 'attribute_abstract';
    const FORM_ATTRIBUTE_SUPER = 'attribute_super';
    const FORM_GENERAL = 'general';
    const FORM_PRICE_AND_TAX = 'price_and_tax';
    const FORM_PRICE_AND_STOCK = 'price_and_stock';
    const FORM_TAX_SET = 'tax_set';
    const FORM_SEO = 'seo';
    const FIELD_NEW_FROM = 'new_from';
    const FIELD_NEW_TO = 'new_to';

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
     * @var \Spryker\Zed\ProductManagement\Communication\Form\DataProvider\LocaleProvider
     */
    protected $localeProvider;

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @var \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface
     */
    protected $productManagementQueryContainer;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToMoneyInterface
     */
    protected $moneyFacade;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToCurrencyInterface
     */
    protected $currencyFacade;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Service\ProductManagementToUtilTextInterface
     */
    protected $utilTextService;

    /**
     * @param \Spryker\Zed\ProductManagement\Communication\Form\DataProvider\LocaleProvider $localeProvider
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     * @param \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface $productManagementQueryContainer
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToMoneyInterface $moneyFacade
     * @param \Spryker\Zed\ProductManagement\Dependency\Service\ProductManagementToUtilTextInterface $utilTextService
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToCurrencyInterface $currencyFacade
     */
    public function __construct(
        LocaleProvider $localeProvider,
        ProductQueryContainerInterface $productQueryContainer,
        ProductManagementQueryContainerInterface $productManagementQueryContainer,
        ProductManagementToMoneyInterface $moneyFacade,
        ProductManagementToUtilTextInterface $utilTextService,
        ProductManagementToCurrencyInterface $currencyFacade
    ) {
        $this->localeProvider = $localeProvider;
        $this->productQueryContainer = $productQueryContainer;
        $this->productManagementQueryContainer = $productManagementQueryContainer;
        $this->moneyFacade = $moneyFacade;
        $this->utilTextService = $utilTextService;
        $this->currencyFacade = $currencyFacade;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ProductFormAdd';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(self::OPTION_ID_LOCALE);
        $resolver->setRequired(self::OPTION_ATTRIBUTE_ABSTRACT);
        $resolver->setRequired(self::OPTION_ATTRIBUTE_SUPER);
        $resolver->setRequired(self::OPTION_TAX_RATES);

        $validationGroups = $this->getValidationGroups();

        $resolver->setDefaults([
            'cascade_validation' => true,
            'required' => false,
            'validation_groups' => function (FormInterface $form) use ($validationGroups) {
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
            self::VALIDATION_GROUP_UNIQUE_SKU,
            self::VALIDATION_GROUP_GENERAL,
            self::VALIDATION_GROUP_PRICE_AND_TAX,
            self::VALIDATION_GROUP_PRICE_AND_STOCK,
            self::VALIDATION_GROUP_ATTRIBUTE_ABSTRACT,
            self::VALIDATION_GROUP_ATTRIBUTE_SUPER,
            self::VALIDATION_GROUP_SEO,
            self::VALIDATION_GROUP_IMAGE_SET,
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
            ->addSeoLocalizedForms($builder)
            ->addImageLocalizedForms($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addGeneralLocalizedForms(FormBuilderInterface $builder)
    {
        $localeCollection = $this->localeProvider->getLocaleCollection();
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
        $localeCollection = $this->localeProvider->getLocaleCollection();
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
        $localeCollection = $this->localeProvider->getLocaleCollection();
        foreach ($localeCollection as $localeTransfer) {
            $name = self::getAbstractAttributeFormName($localeTransfer->getLocaleName());
            $localeTransfer = $this->localeProvider->getLocaleTransfer($localeTransfer->getLocaleName());
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
        $localeCollection = $this->localeProvider->getLocaleCollection(true);
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
    protected function addSkuField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_SKU, 'text', [
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
                        'methods' => [
                            function ($sku, ExecutionContextInterface $context) {
                                $form = $context->getRoot();
                                $idProductAbstract = $form->get(ProductFormAdd::FIELD_ID_PRODUCT_ABSTRACT)->getData();
                                $sku = $this->utilTextService->generateSlug($sku);

                                $skuCount = $this->productQueryContainer
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
                        ],
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
                'class' => 'datepicker js-from-date',
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
                'class' => 'datepicker js-to-date',
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
            ->add(self::FIELD_ID_PRODUCT_ABSTRACT, 'hidden', []);

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
                    'methods' => [
                        function ($dataToValidate, ExecutionContextInterface $context) {
                            $selectedAttributes = array_filter(array_values($dataToValidate));
                            if (empty($selectedAttributes) && !array_key_exists($context->getGroup(), GeneralForm::$errorFieldsDisplayed)) {
                                $context->addViolation('Please enter at least Sku and Name of the product in every locale under General', [$context->getGroup()]);
                                GeneralForm::$errorFieldsDisplayed[$context->getGroup()] = true;
                            }
                        },
                    ],
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
    protected function addAttributeAbstractForm(FormBuilderInterface $builder, $name, LocaleTransfer $localeTransfer = null, array $options = [])
    {
        $builder
            ->add($name, 'collection', [
                'type' => new AttributeAbstractForm(
                    $this->productManagementQueryContainer,
                    $this->localeProvider,
                    $localeTransfer
                ),
                'options' => [
                    AttributeAbstractForm::OPTION_ATTRIBUTE => $options,
                ],
                'label' => false,
                'constraints' => [new Callback([
                    'methods' => [
                        function ($attributes, ExecutionContextInterface $context) {
                            foreach ($attributes as $type => $valueSet) {
                                if ($valueSet[AttributeAbstractForm::FIELD_NAME] && empty($valueSet[AttributeAbstractForm::FIELD_VALUE])) {
                                    $context->addViolation(sprintf(
                                        'Please enter value for product attribute "%s" or disable it',
                                        $type
                                    ));
                                }
                            }
                        },
                    ],
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
            ->add(self::FORM_ATTRIBUTE_SUPER, 'collection', [
                'type' => new AttributeSuperForm(
                    $this->productManagementQueryContainer,
                    $this->localeProvider
                ),
                'options' => [
                    AttributeSuperForm::OPTION_ATTRIBUTE => $options,
                ],
                'label' => false,
                'constraints' => [new Callback([
                    'methods' => [
                        function ($attributes, ExecutionContextInterface $context) {
                            foreach ($attributes as $type => $valueSet) {
                                if ($valueSet[AttributeSuperForm::FIELD_NAME] && empty($valueSet[AttributeSuperForm::FIELD_VALUE])) {
                                    $context->addViolation(sprintf(
                                        'Please enter value for variant attribute "%s" or disable it',
                                        $type
                                    ));
                                }
                            }
                        },
                    ],
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
        $builder
            ->add(self::FORM_PRICE_AND_TAX, new PriceForm($this->moneyFacade, $this->currencyFacade), [
                'label' => false,
                'constraints' => [new Callback([
                    'methods' => [
                        function ($dataToValidate, ExecutionContextInterface $context) {
                            if ((int)$dataToValidate[PriceForm::FIELD_PRICE] < 0) {
                                $context->addViolation('Please enter Price information under Price & Taxes');
                            }

                            if ((int)$dataToValidate[PriceForm::FIELD_TAX_RATE] <= 0) {
                                $context->addViolation('Please enter Tax information under Price & Taxes');
                            }
                        },
                    ],
                    'groups' => [self::VALIDATION_GROUP_PRICE_AND_TAX],
                ])],
                PriceForm::OPTION_TAX_RATE_CHOICES => $options[self::OPTION_TAX_RATES],
                PriceForm::OPTION_CURRENCY_ISO_CODE => $options[static::OPTION_CURRENCY_ISO_CODE],
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
    protected function addImageSetForm(FormBuilderInterface $builder, $name, array $options = [])
    {
        $builder
            ->add($name, 'collection', [
                'type' => new ImageSetForm(),
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'prototype_name' => '__image_set_name__',
                // TODO: move this under ImageSetForm's itself
                /*
                'constraints' => [new Callback([
                    'methods' => [
                        function ($imageSetCollection, ExecutionContextInterface $context) {
                            return;
                            if (array_key_exists($context->getGroup(), GeneralForm::$errorFieldsDisplayed)) {
                                return;
                            }

                            $isEmpty = true;
                            foreach ($imageSetCollection as $setData) {
                                if (trim($setData[ImageSetForm::FIELD_SET_NAME]) !== '') {
                                    $isEmpty = false;
                                    break;
                                }

                                foreach ($setData[ImageSetForm::PRODUCT_IMAGES] as $productImage) {
                                    if (trim($productImage[ImageCollectionForm::FIELD_IMAGE_SMALL]) !== '') {
                                        $isEmpty = false;
                                        break;
                                    }

                                    if (trim($productImage[ImageCollectionForm::FIELD_IMAGE_LARGE]) !== '') {
                                        $isEmpty = false;
                                        break;
                                    }
                                }
                            }

                            if ($isEmpty) {
                                return;
                            }

                            foreach ($imageSetCollection as $setData) {
                                if (trim($setData[ImageSetForm::FIELD_SET_NAME]) === '') {
                                    $context->addViolation('Please enter Image Set Name under Images');
                                    GeneralForm::$errorFieldsDisplayed[$context->getGroup()] = true;
                                }

                                foreach ($setData[ImageSetForm::PRODUCT_IMAGES] as $productImage) {
                                    if (trim($productImage[ImageCollectionForm::FIELD_IMAGE_SMALL]) === '') {
                                        $context->addViolation('Please enter small image url under Images');
                                        GeneralForm::$errorFieldsDisplayed[$context->getGroup()] = true;
                                    }

                                    if (trim($productImage[ImageCollectionForm::FIELD_IMAGE_LARGE]) === '') {
                                        $context->addViolation('Please enter large image url under Images');
                                        GeneralForm::$errorFieldsDisplayed[$context->getGroup()] = true;
                                    }
                                }
                            }
                        },
                    ],
                    'groups' => [self::VALIDATION_GROUP_IMAGE_SET]
                ])]
                */
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
            ->add($name, new SeoForm(), [
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
     * @return \Spryker\Zed\ProductManagement\Communication\Form\Product\GeneralForm
     */
    protected function createGeneralForm()
    {
        return new GeneralForm();
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
