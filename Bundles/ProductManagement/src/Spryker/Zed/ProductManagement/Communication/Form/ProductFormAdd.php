<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form;

use Spryker\Shared\ProductManagement\ProductManagementConstants;
use Spryker\Zed\ProductManagement\Communication\Form\DataProvider\LocaleProvider;
use Spryker\Zed\ProductManagement\Communication\Form\Product\AttributeAbstractForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\AttributeVariantForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\Concrete\StockForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\GeneralForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\PriceForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\SeoForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ProductFormAdd extends AbstractType
{

    const FIELD_SKU = 'sku';
    const FIELD_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    const ATTRIBUTE_ABSTRACT = 'attribute_abstract';
    const ATTRIBUTE_VARIANT = 'attribute_variant';
    const GENERAL = 'general';
    const ID_LOCALE = 'id_locale';
    const PRICE_AND_TAX = 'price_and_tax';
    const PRICE_AND_STOCK = 'price_and_stock';
    const TAX_SET = 'tax_set';
    const SEO = 'seo';

    const VALIDATION_GROUP_ATTRIBUTE_ABSTRACT = 'validation_group_attribute_abstract';
    const VALIDATION_GROUP_ATTRIBUTE_VARIANT = 'validation_group_attribute_variant';
    const VALIDATION_GROUP_GENERAL = 'validation_group_general';
    const VALIDATION_GROUP_PRICE_AND_TAX = 'validation_group_price_and_tax';
    const VALIDATION_GROUP_PRICE_AND_STOCK = 'validation_group_price_and_stock';
    const VALIDATION_GROUP_SEO = 'validation_group_seo';

    const OPTION_TAX_RATES = 'option_tax_rates';

    /**
     * @var \Spryker\Zed\ProductManagement\Communication\Form\DataProvider\LocaleProvider
     */
    protected $localeCollector;

    /**
     * @param \Spryker\Zed\ProductManagement\Communication\Form\DataProvider\LocaleProvider $localeCollection
     */
    public function __construct(LocaleProvider $localeCollection)
    {
        $this->localeCollector = $localeCollection;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ProductFormAdd';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setRequired(self::ID_LOCALE);
        $resolver->setRequired(self::ATTRIBUTE_ABSTRACT);
        $resolver->setRequired(self::ATTRIBUTE_VARIANT);
        $resolver->setRequired(self::OPTION_TAX_RATES);

        $validationGroups = [
            Constraint::DEFAULT_GROUP,
            self::VALIDATION_GROUP_GENERAL,
            self::VALIDATION_GROUP_PRICE_AND_TAX,
            self::VALIDATION_GROUP_PRICE_AND_STOCK,
            self::VALIDATION_GROUP_ATTRIBUTE_ABSTRACT,
            self::VALIDATION_GROUP_ATTRIBUTE_VARIANT,
            self::VALIDATION_GROUP_SEO
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
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addSkuField($builder)
            ->addProductAbstractIdHiddenField($builder)
            ->addGeneralLocalizedForms($builder)
            ->addAttributeAbstractForms($builder, $options[self::ATTRIBUTE_ABSTRACT])
            ->addAttributeVariantForm($builder, $options[self::ATTRIBUTE_VARIANT])
            ->addPriceForm($builder, $options[self::OPTION_TAX_RATES])
            ->addSeoLocalizedForms($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addGeneralLocalizedForms(FormBuilderInterface $builder)
    {
        $localeCollection = $this->localeCollector->getLocaleCollection();
        foreach ($localeCollection as $localeCode) {
            $name = self::getGeneralFormName($localeCode);
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
        $localeCollection = $this->localeCollector->getLocaleCollection();
        foreach ($localeCollection as $localeCode) {
            $name = self::getSeoFormName($localeCode);
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
        $localeCollection = $this->localeCollector->getLocaleCollection(true);
        foreach ($localeCollection as $localeCode) {
            $name = self::getAbstractAttributeFormName($localeCode);
            $this->addAttributeAbstractForm($builder, $name, $options);
        }

        $defaultName = ProductFormAdd::getLocalizedPrefixName(ProductFormAdd::ATTRIBUTE_ABSTRACT, ProductManagementConstants::PRODUCT_MANAGEMENT_DEFAULT_LOCALE);
        $this->addAttributeAbstractForm($builder, $defaultName, $options);

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
                'label' => 'SKU',
                'required' => true,
                'constraints' => [
                    new Callback([
                        'methods' => [
                            function ($dataToValidate, ExecutionContextInterface $context) {
                                //TODO more sophisticated validation
                                if (!($dataToValidate)) {
                                    $context->addViolation('Please enter valid SKU, it may consist of alphanumeric characters with dashes or dots.');
                                }
                            },
                        ],
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
            ->add($name, new GeneralForm(self::GENERAL), [
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
                    'groups' => [self::VALIDATION_GROUP_GENERAL]
                ])]
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
    protected function addAttributeAbstractForm(FormBuilderInterface $builder, $name, array $options = [])
    {
        $builder
            ->add($name, 'collection', [
                'type' => new AttributeAbstractForm(self::ATTRIBUTE_ABSTRACT),
                'options' => [
                    AttributeAbstractForm::OPTION_ATTRIBUTE => $options,
                ],
                'label' => false,
                'constraints' => [new Callback([
                    'methods' => [
                        function ($attributes, ExecutionContextInterface $context) {
                            return;
                            sd($attributes);
                            $selectedAttributes = [];
                            foreach ($attributes as $type => $valueSet) {
                                if (!empty($valueSet['value'])) {
                                    $selectedAttributes[] = $valueSet['value'];
                                    break;
                                }
                            }

                            if (empty($selectedAttributes) && !array_key_exists($context->getGroup(), GeneralForm::$errorFieldsDisplayed)) {
                                $context->addViolation('Please select at least one attribute and its value under Attributes');
                                GeneralForm::$errorFieldsDisplayed[$context->getGroup()] = true;
                            }
                        },
                    ],
                    'groups' => [self::VALIDATION_GROUP_ATTRIBUTE_ABSTRACT]
                ])]
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addAttributeVariantForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add(self::ATTRIBUTE_VARIANT, 'collection', [
                'type' => new AttributeVariantForm(self::ATTRIBUTE_ABSTRACT),
                'options' => [
                    AttributeVariantForm::OPTION_ATTRIBUTE => $options,
                ],
                'label' => false,
                'constraints' => [new Callback([
                    'methods' => [
                        function ($attributes, ExecutionContextInterface $context) {
                            return;
                            $selectedAttributes = [];
                            foreach ($attributes as $type => $valueSet) {
                                if (!empty($valueSet['value'])) {
                                    $selectedAttributes[] = $valueSet['value'];
                                    break;
                                }
                            }

                            if (empty($selectedAttributes)) {
                                $context->addViolation('Please select at least one variant attribute value');
                            }
                        },
                    ],
                    'groups' => [self::VALIDATION_GROUP_ATTRIBUTE_VARIANT]
                ])]
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
            ->add(self::PRICE_AND_TAX, new PriceForm($options), [
                'label' => false,
                'constraints' => [new Callback([
                    'methods' => [
                        function ($dataToValidate, ExecutionContextInterface $context) {
                            if ((int)$dataToValidate[PriceForm::FIELD_PRICE] <= 0) {
                                $context->addViolation('Please enter Price information under Price & Taxes');
                            }

                            if ((int)$dataToValidate[PriceForm::FIELD_TAX_RATE] <= 0) {
                                $context->addViolation('Please enter Tax information under Price & Taxes');
                            }
                        },
                    ],
                    'groups' => [self::VALIDATION_GROUP_PRICE_AND_TAX]
                ])]
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
            ->add($name, new SeoForm(self::SEO), [
                'label' => false,
            ]);

        return $this;
    }

    /**
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
        return self::getLocalizedPrefixName(self::GENERAL, $localeCode);
    }

    /**
     * @param string $localeCode
     *
     * @return string
     */
    public static function getSeoFormName($localeCode)
    {
        return self::getLocalizedPrefixName(self::SEO, $localeCode);
    }

    /**
     * @param string $localeCode
     *
     * @return string
     */
    public static function getAbstractAttributeFormName($localeCode)
    {
        return self::getLocalizedPrefixName(self::ATTRIBUTE_ABSTRACT, $localeCode);
    }

}
