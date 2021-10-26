<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiConfig getConfig()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 */
class ProductConcreteEditForm extends AbstractType
{
    /**
     * @var string
     */
    protected const FIELD_PRODUCT_CONCRETE = 'productConcrete';

    /**
     * @var string
     */
    protected const FIELD_USE_ABSTRACT_PRODUCT_NAME = 'useAbstractProductName';

    /**
     * @var string
     */
    protected const FIELD_USE_ABSTRACT_PRODUCT_DESCRIPTION = 'useAbstractProductDescription';

    /**
     * @var string
     */
    protected const FIELD_USE_ABSTRACT_PRODUCT_PRICES = 'useAbstractProductPrices';

    /**
     * @var string
     */
    protected const FIELD_USE_ABSTRACT_PRODUCT_IMAGE_SETS = 'useAbstractProductImageSets';

    /**
     * @var string
     */
    protected const FIELD_SEARCHABILITY = 'searchability';

    /**
     * @var string
     */
    protected const BLOCK_PREFIX = 'productConcreteEdit';

    /**
     * @var string
     */
    protected const OPTION_SEARCHABILITY_CHOICES = 'OPTION_SEARCHABILITY_CHOICES';

    /**
     * @var string
     */
    protected const LABEL_USE_ABSTRACT_PRODUCT_NAME = 'Use Abstract Product Name';

    /**
     * @var string
     */
    protected const LABEL_USE_ABSTRACT_PRODUCT_DESCRIPTION = 'Use Abstract Product Description';

    /**
     * @var string
     */
    protected const LABEL_USE_ABSTRACT_PRODUCT_PRICES = 'Use Abstract Product prices';

    /**
     * @var string
     */
    protected const LABEL_USE_ABSTRACT_PRODUCT_IMAGE_SETS = 'Use Abstract Product Image Sets';

    /**
     * @var string
     */
    protected const LABEL_SEARCHABILITY = 'Searchability';

    /**
     * @var string
     */
    protected const PLACEHOLDER_SEARCHABILITY = 'Select Locales';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductConcreteForm::BLOCK_PREFIX
     *
     * @var string
     */
    protected const BLOCK_PREFIX_PRODUCT_CONCRETE_FORM = 'productConcrete';

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
        $resolver->setRequired(static::OPTION_SEARCHABILITY_CHOICES);
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
        $this
            ->addProductConcreteSubForm($builder)
            ->addUseAbstractProductNameField($builder)
            ->addUseAbstractProductDescriptionField($builder)
            ->addUseAbstractProductPricesField($builder)
            ->addUseAbstractProductImageSetsField($builder)
            ->addSearchabilityField($builder, $options);

        $builder->addModelTransformer($this->getFactory()->createProductConcreteEditFormDataTransformer());
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
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addUseAbstractProductNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_USE_ABSTRACT_PRODUCT_NAME, CheckboxType::class, [
            'required' => false,
            'label' => static::LABEL_USE_ABSTRACT_PRODUCT_NAME,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addUseAbstractProductDescriptionField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_USE_ABSTRACT_PRODUCT_DESCRIPTION, CheckboxType::class, [
            'required' => false,
            'label' => static::LABEL_USE_ABSTRACT_PRODUCT_DESCRIPTION,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductConcreteSubForm(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PRODUCT_CONCRETE, ProductConcreteForm::class, [
            'constraints' => [
                $this->getFactory()->createProductConcreteOwnedByMerchantConstraint(),
                new NotBlank(),
            ],
            'label' => false,
        ]);

        /** @var \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer */
        $productConcreteTransfer = $builder->getData()[static::BLOCK_PREFIX_PRODUCT_CONCRETE_FORM];
        $priceProductTransformer = $this->getFactory()
            ->createPriceProductTransformer()
            ->setIdProductConcrete($productConcreteTransfer->getIdProductConcreteOrFail())
            ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstractOrFail());

        $builder->get(static::FIELD_PRODUCT_CONCRETE)->get(ProductConcreteTransfer::PRICES)->addModelTransformer(
            $priceProductTransformer,
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addUseAbstractProductPricesField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_USE_ABSTRACT_PRODUCT_PRICES, CheckboxType::class, [
            'required' => false,
            'label' => static::LABEL_USE_ABSTRACT_PRODUCT_PRICES,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addUseAbstractProductImageSetsField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_USE_ABSTRACT_PRODUCT_IMAGE_SETS, CheckboxType::class, [
            'required' => false,
            'label' => static::LABEL_USE_ABSTRACT_PRODUCT_IMAGE_SETS,
        ]);

        return $this;
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @phpstan-param array<mixed> $options
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addSearchabilityField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_SEARCHABILITY,
            ChoiceType::class,
            [
                'choices' => $options[static::OPTION_SEARCHABILITY_CHOICES],
                'multiple' => true,
                'label' => static::LABEL_SEARCHABILITY,
                'required' => false,
                'attr' => [
                    'placeholder' => static::PLACEHOLDER_SEARCHABILITY,
                ],
            ],
        );

        return $this;
    }
}
