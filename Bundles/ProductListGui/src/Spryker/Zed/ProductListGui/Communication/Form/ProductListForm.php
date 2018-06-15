<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Form;

use Generated\Shared\Transfer\ProductListTransfer;
use Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListCreateFormExpanderPluginInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

/**
 * @method \Spryker\Zed\ProductListGui\Communication\ProductListGuiCommunicationFactory getFactory()
 */
class ProductListForm extends AbstractType
{
    public const FIELD_ID = ProductListTransfer::ID_PRODUCT_LIST;
    public const FIELD_NAME = ProductListTransfer::TITLE;
    public const FIELD_TYPE = ProductListTransfer::TYPE;
    public const FIELD_OWNER_TYPE = 'owner_type';
    public const FIELD_CATEGORIES = ProductListTransfer::PRODUCT_LIST_CATEGORY_RELATION;
    public const FIELD_PRODUCTS = ProductListTransfer::PRODUCT_LIST_PRODUCT_CONCRETE_RELATION;

    public const OPTION_DISABLE_GENERAL = ProductListCreateFormExpanderPluginInterface::OPTION_DISABLE_GENERAL;
    public const OPTION_OWNER_TYPES = 'OPTION_OWNER_TYPES';

    public const BLOCK_PREFIX = 'productList';

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
        $resolver->setRequired([
            static::FIELD_CATEGORIES,
            static::FIELD_PRODUCTS,
            static::OPTION_DISABLE_GENERAL,
            static::OPTION_OWNER_TYPES,
            'merchant-relation-names',
        ]);

        $resolver->setDefaults([
            'data_class' => ProductListTransfer::class,
            static::OPTION_DISABLE_GENERAL => true,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string[] $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addIdField($builder, $options[static::OPTION_DISABLE_GENERAL])
            ->addNameField($builder, $options[static::OPTION_DISABLE_GENERAL])
            ->addTypeFiled($builder, $options[static::OPTION_DISABLE_GENERAL])
            ->addOwnerTypeField(
                $builder,
                $options[static::OPTION_DISABLE_GENERAL],
                $options[static::OPTION_OWNER_TYPES]
            )
            ->addExtensionFields($builder, $options)
            ->addCategoriesSubForm($builder, $options[static::FIELD_CATEGORIES])
            ->addProductsSubForm($builder, $options[static::FIELD_PRODUCTS]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param bool $disabled
     *
     * @return $this
     */
    protected function addIdField(FormBuilderInterface $builder, bool $disabled): self
    {
        $builder->add(static::FIELD_ID, HiddenType::class, [
            'disabled' => $disabled,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param bool $disabled
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder, bool $disabled): self
    {
        $builder->add(static::FIELD_NAME, TextType::class, [
            'label' => 'Name',
            'disabled' => $disabled,
            'constraints' => [
                new Required(),
                new NotBlank(),
                new Length(['max' => 100]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param bool $disabled
     *
     * @return $this
     */
    protected function addTypeFiled(FormBuilderInterface $builder, bool $disabled): self
    {
        $builder->add(static::FIELD_TYPE, ChoiceType::class, [
            'label' => 'Type',
            'disabled' => $disabled,
            'expanded' => true,
            'multiple' => false,
            'choices' => [
                'Whitelist' => SpyProductListTableMap::COL_TYPE_WHITELIST,
                'Blacklist' => SpyProductListTableMap::COL_TYPE_BLACKLIST,
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param bool $disabled
     * @param string[] [<choice name> => <choice value>] $ownerTypeChoices
     *
     * @return $this
     */
    protected function addOwnerTypeField(FormBuilderInterface $builder, bool $disabled, array $ownerTypeChoices): self
    {
        $builder->add(static::FIELD_OWNER_TYPE, ChoiceType::class, [
            'label' => 'Owner type',
            'disabled' => $disabled,
            'required' => true,
            'mapped' => false,
            'choices' => $ownerTypeChoices,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addExtensionFields(FormBuilderInterface $builder, array $options): self
    {
        $plugins = $this->getFactory()->getProductListCreateFormExpanderPlugins();

        foreach ($plugins as $plugin) {
            $plugin->buildForm($builder, $options);
        }

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addCategoriesSubForm(FormBuilderInterface $builder, array $options): self
    {
        $builder->add(static::FIELD_CATEGORIES, ProductListCategoryRelationType::class, $options);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addProductsSubForm(FormBuilderInterface $builder, array $options): self
    {
        $builder->add(static::FIELD_PRODUCTS, ProductListProductConcreteRelationType::class, $options);

        return $this;
    }
}
