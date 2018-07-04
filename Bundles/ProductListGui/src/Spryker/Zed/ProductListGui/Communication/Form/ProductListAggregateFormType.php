<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Form;

use Generated\Shared\Transfer\ProductListAggregateFormTransfer;
use Generated\Shared\Transfer\ProductListCategoryRelationTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductListAggregateFormType extends AbstractType
{
    public const OPTION_CATEGORY_IDS = ProductListCategoryRelationTransfer::CATEGORY_IDS;
    public const OPTION_OWNER_TYPE = ProductListAggregateFormTransfer::OWNER_TYPE;

    public const BLOCK_PREFIX = 'productListAggregate';

    public const FIELD_ASSIGNED_PRODUCT_IDS = ProductListAggregateFormTransfer::ASSIGNED_PRODUCT_IDS;
    public const FIELD_PRODUCT_IDS_TO_BE_ASSIGNED = ProductListAggregateFormTransfer::PRODUCT_IDS_TO_BE_ASSIGNED;
    public const FIELD_PRODUCT_IDS_TO_BE_DEASSIGNED = ProductListAggregateFormTransfer::PRODUCT_IDS_TO_BE_DE_ASSIGNED;

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(static::OPTION_CATEGORY_IDS);
        $resolver->setRequired(static::OPTION_OWNER_TYPE);

        $resolver->setDefaults([
            'data_class' => ProductListAggregateFormTransfer::class,
            'attr' => [
                'novalidate' => 'novalidate',
            ],
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::BLOCK_PREFIX;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addAssignedProductIdsField($builder)
            ->addProductIdsToBeAssignedField($builder)
            ->addProductIdsToBeDeassignedField($builder)
            ->addOwnerTypeField($builder, $options)
            ->addProductListSubForm($builder)
            ->addProductListCategoryRelationSubForm($builder, $options)
            ->addProductListProductConcreteRelationSubForm($builder);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'onPreSubmit']);
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $formEvent
     *
     * @return void
     */
    public function onPreSubmit(FormEvent $formEvent): void
    {
        if (!$formEvent->getForm()->isValid()) {
            return;
        }
        $data = $formEvent->getData();
        $assignedProductIds = $data[static::FIELD_ASSIGNED_PRODUCT_IDS]
            ? preg_split('/,/', $data[static::FIELD_ASSIGNED_PRODUCT_IDS], null, PREG_SPLIT_NO_EMPTY)
            : [];
        $productIdsToBeAssigned = $data[static::FIELD_PRODUCT_IDS_TO_BE_ASSIGNED]
            ? preg_split('/,/', $data[static::FIELD_PRODUCT_IDS_TO_BE_ASSIGNED], null, PREG_SPLIT_NO_EMPTY)
            : [];
        $productIdsToBeDeassigned = $data[static::FIELD_PRODUCT_IDS_TO_BE_DEASSIGNED]
            ? preg_split('/,/', $data[static::FIELD_PRODUCT_IDS_TO_BE_DEASSIGNED], null, PREG_SPLIT_NO_EMPTY)
            : [];

        $assignedProductIds = array_unique(array_merge($assignedProductIds, $productIdsToBeAssigned));
        $assignedProductIds = array_diff($assignedProductIds, $productIdsToBeDeassigned);
        $data[ProductListAggregateFormTransfer::PRODUCT_LIST_PRODUCT_CONCRETE_RELATION][ProductListProductConcreteRelationFormType::PRODUCT_IDS] = $assignedProductIds;

        $formEvent->setData($data);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAssignedProductIdsField(FormBuilderInterface $builder): self
    {
        $builder->add(
            static::FIELD_ASSIGNED_PRODUCT_IDS,
            HiddenType::class
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductIdsToBeAssignedField(FormBuilderInterface $builder): self
    {
        $builder->add(
            static::FIELD_PRODUCT_IDS_TO_BE_ASSIGNED,
            HiddenType::class
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductIdsToBeDeassignedField(FormBuilderInterface $builder): self
    {
        $builder->add(
            static::FIELD_PRODUCT_IDS_TO_BE_DEASSIGNED,
            HiddenType::class
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductListSubForm(FormBuilderInterface $builder): self
    {

        $builder->add(
            ProductListAggregateFormTransfer::PRODUCT_LIST,
            ProductListFormType::class
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addOwnerTypeField($builder, $options): self
    {
        $builder->add(ProductListAggregateFormTransfer::OWNER_TYPE, ChoiceType::class, [
            'label' => 'Owner Type',
            'required' => true,
            'choices' => $options[static::OPTION_OWNER_TYPE],
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addProductListCategoryRelationSubForm(FormBuilderInterface $builder, array $options): self
    {
        $builder->add(
            ProductListAggregateFormTransfer::PRODUCT_LIST_CATEGORY_RELATION,
            ProductListCategoryRelationFormType::class,
            $this->getCategoryIdsOptions($options)
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductListProductConcreteRelationSubForm(FormBuilderInterface $builder): self
    {
        $builder->add(
            ProductListAggregateFormTransfer::PRODUCT_LIST_PRODUCT_CONCRETE_RELATION,
            ProductListProductConcreteRelationFormType::class
        );

        return $this;
    }

    /**
     * @param array $options
     *
     * @return array
     */
    protected function getCategoryIdsOptions(array $options): array
    {
        return [static::OPTION_CATEGORY_IDS => $options[static::OPTION_CATEGORY_IDS]];
    }
}
