<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Expander;

use Generated\Shared\Transfer\ProductListAggregateFormTransfer;
use Spryker\Zed\ProductListGui\Communication\Form\ProductListAggregateFormType;
use Spryker\Zed\ProductListGui\Communication\Form\ProductListCategoryRelationFormType;
use Spryker\Zed\ProductListGui\Communication\Form\ProductListProductConcreteRelationFormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ProductListAggregateFormExpander implements ProductListAggregateFormExpanderInterface
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expandWithProductListCategoryRelationForm(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        return $builder->add(
            ProductListAggregateFormTransfer::PRODUCT_LIST_CATEGORY_RELATION,
            ProductListCategoryRelationFormType::class,
            [ProductListAggregateFormType::OPTION_CATEGORY_IDS => $options[ProductListAggregateFormType::OPTION_CATEGORY_IDS]]
        );
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expandWithProductListProductConcreteRelationForm(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $builder->add(
            ProductListAggregateFormTransfer::PRODUCT_LIST_PRODUCT_CONCRETE_RELATION,
            ProductListProductConcreteRelationFormType::class
        );

        $this->addProductListProductConcreteRelationFormHelperFields($builder);
        $builder->addEventListener(FormEvents::POST_SUBMIT, [$this, 'postSubmitEventHandler']);

        return $builder;
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $formEvent
     *
     * @return void
     */
    public function postSubmitEventHandler(FormEvent $formEvent): void
    {
        $data = $formEvent->getData();

        $assignedProductIds = $data[ProductListProductConcreteRelationFormType::FIELD_ASSIGNED_PRODUCT_IDS]
            ? preg_split('/,/', $data[ProductListProductConcreteRelationFormType::FIELD_ASSIGNED_PRODUCT_IDS], null, PREG_SPLIT_NO_EMPTY)
            : [];
        $productIdsToBeAssigned = $data[ProductListProductConcreteRelationFormType::FIELD_PRODUCT_IDS_TO_BE_ASSIGNED]
            ? preg_split('/,/', $data[ProductListProductConcreteRelationFormType::FIELD_PRODUCT_IDS_TO_BE_ASSIGNED], null, PREG_SPLIT_NO_EMPTY)
            : [];
        $productIdsToBeDeassigned = $data[ProductListProductConcreteRelationFormType::FIELD_PRODUCT_IDS_TO_BE_DEASSIGNED]
            ? preg_split('/,/', $data[ProductListProductConcreteRelationFormType::FIELD_PRODUCT_IDS_TO_BE_DEASSIGNED], null, PREG_SPLIT_NO_EMPTY)
            : [];

        $assignedProductIds = array_unique(array_merge($assignedProductIds, $productIdsToBeAssigned));
        $assignedProductIds = array_diff($assignedProductIds, $productIdsToBeDeassigned);
        $data[ProductListAggregateFormTransfer::PRODUCT_LIST_PRODUCT_CONCRETE_RELATION][ProductListProductConcreteRelationFormType::PRODUCT_IDS] = $assignedProductIds;

        $formEvent->setData($data);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addProductListProductConcreteRelationFormHelperFields(FormBuilderInterface $builder): void
    {
        $builder->add(
            ProductListProductConcreteRelationFormType::FIELD_ASSIGNED_PRODUCT_IDS,
            HiddenType::class
        );

        $builder->add(
            ProductListProductConcreteRelationFormType::FIELD_PRODUCT_IDS_TO_BE_ASSIGNED,
            HiddenType::class
        );

        $builder->add(
            ProductListProductConcreteRelationFormType::FIELD_PRODUCT_IDS_TO_BE_DEASSIGNED,
            HiddenType::class
        );
    }
}
