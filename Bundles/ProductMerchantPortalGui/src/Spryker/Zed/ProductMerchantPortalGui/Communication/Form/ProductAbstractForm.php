<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\EventSubscriber\ProductImageSetsEventSubscriber;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Type\ProductImageSetFormType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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
class ProductAbstractForm extends AbstractType
{
    /**
     * @var string
     */
    public const OPTION_STORE_CHOICES = 'OPTION_STORE_CHOICES';
    /**
     * @var string
     */
    public const OPTION_PRODUCT_CATEGORY_CHOICES = 'OPTION_PRODUCT_CATEGORY_CHOICES';

    /**
     * @var string
     */
    public const BLOCK_PREFIX = 'productAbstract';

    public const GROUP_WITH_STORES = self::FIELD_STORES;

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductLocalizedAttributesForm::NAME_VALIDATION_GROUP
     * @var string
     */
    protected const NAME_VALIDATION_GROUP = 'name_validation_group';
    /**
     * @var string
     */
    protected const FIELD_STORES = 'stores';

    /**
     * @var string
     */
    protected const LABEL_STORES = 'Stores';
    /**
     * @var string
     */
    protected const LABEL_CATEGORIES = 'Categories';

    /**
     * @var string
     */
    protected const PLACEHOLDER_STORES = 'Select';
    /**
     * @var string
     */
    protected const PLACEHOLDER_CATEGORIES = 'Select';

    /**
     * @var string
     */
    protected const KEY_OPTIONS_DATA = 'data';
    /**
     * @var string
     */
    protected const KEY_OPTIONS_ATTRIBUTES = 'attributes';

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
            'data_class' => ProductAbstractTransfer::class,
            'validation_groups' => function (FormInterface $form) {
                $validationGroups = [Constraint::DEFAULT_GROUP, static::NAME_VALIDATION_GROUP];

                if ($form->get(static::FIELD_STORES)->getData()) {
                    $validationGroups[] = static::GROUP_WITH_STORES;
                }

                return $validationGroups;
            },
        ]);

        $resolver->setRequired(static::OPTION_STORE_CHOICES);
        $resolver->setRequired(static::OPTION_PRODUCT_CATEGORY_CHOICES);
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param mixed[] $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addLocalizedAttributesSubform($builder)
            ->addStoresField($builder, $options)
            ->addPrices($builder, $options)
            ->addCategories($builder, $options)
            ->addProductImageSets($builder)
            ->addAttributes($builder);

        $builder->addEventSubscriber(new ProductImageSetsEventSubscriber());

        $this->executeProductAbstractFormExpanderPlugins($builder, $options);
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductImageSets(FormBuilderInterface $builder)
    {
        $builder->add(ProductAbstractTransfer::IMAGE_SETS, CollectionType::class, [
            'label' => false,
            'entry_type' => ProductImageSetFormType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'allow_extra_fields' => true,
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
    protected function addLocalizedAttributesSubform(FormBuilderInterface $builder)
    {
        $builder->add(ProductAbstractTransfer::LOCALIZED_ATTRIBUTES, CollectionType::class, [
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
     * @param mixed[] $options
     *
     * @return $this
     */
    protected function addStoresField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_STORES,
            ChoiceType::class,
            [
                'choices' => $options[static::OPTION_STORE_CHOICES],
                'multiple' => true,
                'label' => static::LABEL_STORES,
                'required' => false,
                'empty_data' => [],
                'attr' => [
                    'placeholder' => static::PLACEHOLDER_STORES,
                ],
                'property_path' => 'storeRelation.idStores',
            ]
        );

        return $this;
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param mixed[] $options
     *
     * @return $this
     */
    protected function addPrices(FormBuilderInterface $builder, array $options)
    {
        $builder->add(ProductAbstractTransfer::PRICES, HiddenType::class, [
            'required' => false,
            'label' => false,
        ]);

        $idProductAbstract = $options[static::KEY_OPTIONS_DATA]->getIdProductAbstract();

        $priceProductTransformer = $this->getFactory()
            ->createPriceProductTransformer()
            ->setIdProductAbstract($idProductAbstract);

        $builder->get(ProductAbstractTransfer::PRICES)->addModelTransformer($priceProductTransformer);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAttributes(FormBuilderInterface $builder)
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
     * @param mixed[] $options
     *
     * @return $this
     */
    protected function addCategories(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            ProductAbstractTransfer::CATEGORY_IDS,
            ChoiceType::class,
            [
                'choices' => $options[static::OPTION_PRODUCT_CATEGORY_CHOICES],
                'multiple' => true,
                'label' => static::LABEL_CATEGORIES,
                'required' => false,
                'empty_data' => [],
                'attr' => [
                    'placeholder' => static::PLACEHOLDER_CATEGORIES,
                ],
            ]
        );

        return $this;
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param mixed[] $options
     *
     * @return $this
     */
    protected function executeProductAbstractFormExpanderPlugins(FormBuilderInterface $builder, array $options)
    {
        foreach ($this->getFactory()->getProductAbstractFormExpanderPlugins() as $productAbstractFormExpanderPlugin) {
            $builder = $productAbstractFormExpanderPlugin->expand($builder, $options);
        }

        return $this;
    }
}
