<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form;

use DateTime;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint\ValidFromRangeConstraint;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint\ValidToRangeConstraint;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\EventSubscriber\ProductImageSetsEventSubscriber;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Type\ProductImageSetFormType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiConfig getConfig()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 */
class ProductConcreteForm extends AbstractType
{
    /**
     * @var string
     */
    protected const BLOCK_PREFIX = 'productConcrete';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductConcreteEditForm::FIELD_USE_ABSTRACT_PRODUCT_NAME
     * @var string
     */
    protected const FIELD_USE_ABSTRACT_PRODUCT_NAME = 'useAbstractProductName';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductLocalizedAttributesForm::NAME_VALIDATION_GROUP
     * @var string
     */
    protected const NAME_VALIDATION_GROUP = 'name_validation_group';

    /**
     * @var string
     */
    protected const LABEL_VALID_FROM = 'From';
    /**
     * @var string
     */
    protected const LABEL_VALID_TO = 'To';
    /**
     * @var string
     */
    protected const LABEL_STOCK = 'Stock';

    /**
     * @var string
     */
    protected const DATE_TIME_FORMAT = 'Y-m-d H:i:s';
    /**
     * @var string
     */
    protected const WIDGET_SINGLE_TEXT = 'single_text';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::BLOCK_PREFIX;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductConcreteTransfer::class,
            'validation_groups' => function (FormInterface $form) {
                $validationGroups = [Constraint::DEFAULT_GROUP];

                $parentForm = $form->getParent();

                if ($parentForm) {
                    $useAbstractProductName = $parentForm->get(static::FIELD_USE_ABSTRACT_PRODUCT_NAME)->getData();

                    if (!$useAbstractProductName) {
                        $validationGroups[] = static::NAME_VALIDATION_GROUP;
                    }
                }

                return $validationGroups;
            },
        ]);
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @phpstan-param array<mixed> $options
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addLocalizedAttributesSubform($builder)
            ->addIsActiveField($builder)
            ->addStockSubform($builder)
            ->addValidFromField($builder)
            ->addValidToField($builder)
            ->addPricesField($builder)
            ->addProductImageSetsField($builder)
            ->addAttributesField($builder);

        $builder->addEventSubscriber(new ProductImageSetsEventSubscriber());
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductImageSetsField(FormBuilderInterface $builder)
    {
        $builder->add(ProductConcreteTransfer::IMAGE_SETS, CollectionType::class, [
            'label' => false,
            'entry_type' => ProductImageSetFormType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'allow_extra_fields' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAttributesField(FormBuilderInterface $builder)
    {
        $builder->add(ProductAbstractTransfer::ATTRIBUTES, HiddenType::class, [
            'required' => false,
            'label' => false,
            'constraints' => [
                $this->getFactory()->createProductAttributesNotBlankConstraint(),
                $this->getFactory()->createAbstractProductAttributeUniqueCombinationConstraint(),
            ],
        ]);

        $attributeProductTransformer = $this->getFactory()->createAttributeProductTransformer();

        $builder->get(ProductAbstractTransfer::ATTRIBUTES)->addModelTransformer($attributeProductTransformer);

        return $this;
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addLocalizedAttributesSubform(FormBuilderInterface $builder)
    {
        $builder->add(ProductConcreteTransfer::LOCALIZED_ATTRIBUTES, CollectionType::class, [
            'label' => false,
            'entry_type' => ProductLocalizedAttributesForm::class,
            'allow_add' => true,
            'allow_delete' => true,
        ]);

        return $this;
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsActiveField(FormBuilderInterface $builder)
    {
        $builder->add(
            ProductConcreteTransfer::IS_ACTIVE,
            CheckboxType::class,
            [
                'required' => false,
            ]
        );

        return $this;
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValidFromField(FormBuilderInterface $builder)
    {
        $builder->add(ProductConcreteTransfer::VALID_FROM, DateTimeType::class, [
            'required' => false,
            'label' => static::LABEL_VALID_FROM,
            'constraints' => [
                new ValidFromRangeConstraint(),
            ],
            'widget' => static::WIDGET_SINGLE_TEXT,
        ]);

        $builder->get(ProductConcreteTransfer::VALID_FROM)
            ->addModelTransformer($this->createDateTimeModelTransformer());

        return $this;
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValidToField(FormBuilderInterface $builder)
    {
        $builder->add(ProductConcreteTransfer::VALID_TO, DateTimeType::class, [
            'required' => false,
            'label' => static::LABEL_VALID_TO,
            'constraints' => [
                new ValidToRangeConstraint(),
            ],
            'widget' => static::WIDGET_SINGLE_TEXT,
        ]);

        $builder->get(ProductConcreteTransfer::VALID_TO)
            ->addModelTransformer($this->createDateTimeModelTransformer());

        return $this;
    }

    /**
     * @return \Symfony\Component\Form\CallbackTransformer
     */
    protected function createDateTimeModelTransformer(): CallbackTransformer
    {
        return new CallbackTransformer(
            function ($value) {
                if ($value !== null) {
                    return new DateTime($value);
                }
            },
            function ($value) {
                if ($value instanceof DateTime) {
                    $value = $value->format(static::DATE_TIME_FORMAT);
                }

                return $value;
            }
        );
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPricesField(FormBuilderInterface $builder)
    {
        $builder->add(ProductConcreteTransfer::PRICES, HiddenType::class, [
            'required' => false,
            'label' => false,
        ]);

        return $this;
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addStockSubform(FormBuilderInterface $builder)
    {
        $builder->add(ProductConcreteTransfer::STOCKS, StockProductForm::class, [
            'label' => static::LABEL_STOCK,
        ]);

        $builder->get(ProductConcreteTransfer::STOCKS)
            ->addModelTransformer($this->getFactory()->createStockTransformer());

        return $this;
    }
}
