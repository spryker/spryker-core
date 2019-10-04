<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Expander;

use Generated\Shared\Transfer\ProductListAggregateFormTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
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
     * @return void
     */
    public function expandWithProductListAssignmentForms(FormBuilderInterface $builder, array $options): void
    {
        $this->addProductListCategoryRelationForm($builder, $options)
            ->addProductListProductConcreteRelationForm($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $formEvent
     *
     * @return void
     */
    public function postSubmitEventHandler(FormEvent $formEvent): void
    {
        $data = $formEvent->getData();

        $assignedProductIdsData = $this->getFieldValue(ProductListProductConcreteRelationFormType::FIELD_ASSIGNED_PRODUCT_IDS, $formEvent);
        $productIdsToBeAssignedData = $this->getFieldValue(ProductListProductConcreteRelationFormType::FIELD_PRODUCT_IDS_TO_BE_ASSIGNED, $formEvent);
        $productIdsToBeDeassignedData = $this->getFieldValue(ProductListProductConcreteRelationFormType::FIELD_PRODUCT_IDS_TO_BE_DEASSIGNED, $formEvent);

        $assignedProductIds = $assignedProductIdsData
            ? preg_split('/,/', $assignedProductIdsData, null, PREG_SPLIT_NO_EMPTY)
            : [];

        $productIdsToBeAssigned = $productIdsToBeAssignedData
            ? preg_split('/,/', $productIdsToBeAssignedData, null, PREG_SPLIT_NO_EMPTY)
            : [];
        $productIdsToBeDeassigned = $productIdsToBeDeassignedData
            ? preg_split('/,/', $productIdsToBeDeassignedData, null, PREG_SPLIT_NO_EMPTY)
            : [];

        $assignedProductIds = array_unique(array_merge($assignedProductIds, $productIdsToBeAssigned));
        $assignedProductIds = array_diff($assignedProductIds, $productIdsToBeDeassigned);

        /**
         * @var \Generated\Shared\Transfer\ProductListAggregateFormTransfer $productListProductConcreteRelationTransfer
         */
        $productListProductConcreteRelationTransfer = $this->getFieldValue(ProductListAggregateFormTransfer::PRODUCT_LIST_PRODUCT_CONCRETE_RELATION, $formEvent);
        $productListProductConcreteRelationTransfer->offsetSet(ProductListProductConcreteRelationFormType::PRODUCT_IDS, $assignedProductIds);

        $formEvent->setData($data);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addProductListCategoryRelationForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            ProductListAggregateFormTransfer::PRODUCT_LIST_CATEGORY_RELATION,
            ProductListCategoryRelationFormType::class,
            [ProductListAggregateFormType::OPTION_CATEGORY_IDS => $options[ProductListAggregateFormType::OPTION_CATEGORY_IDS]]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductListProductConcreteRelationForm(FormBuilderInterface $builder)
    {
        $builder->add(
            ProductListAggregateFormTransfer::PRODUCT_LIST_PRODUCT_CONCRETE_RELATION,
            ProductListProductConcreteRelationFormType::class
        );

        $this->addProductListProductConcreteRelationFormHelperFields($builder);
        $builder->addEventListener(FormEvents::POST_SUBMIT, [$this, 'postSubmitEventHandler']);

        return $this;
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

    /**
     * @param string $fieldName
     * @param \Symfony\Component\Form\FormEvent $formEvent
     *
     * @return mixed
     */
    protected function getFieldValue(string $fieldName, FormEvent $formEvent)
    {
        return $this->getFieldValueByPropertyPath(
            $formEvent->getData(),
            $this->getFieldPropertyPath($fieldName, $formEvent)
        );
    }

    /**
     * @param string $fieldName
     * @param \Symfony\Component\Form\FormEvent $formEvent
     *
     * @return string
     */
    protected function getFieldPropertyPath(string $fieldName, FormEvent $formEvent): string
    {
        return $formEvent->getForm()
            ->get($fieldName)
            ->getPropertyPath()
            ->__toString();
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $abstractTransfer
     * @param string $propertyPath
     *
     * @return mixed
     */
    protected function getFieldValueByPropertyPath(AbstractTransfer $abstractTransfer, string $propertyPath)
    {
        $current = $abstractTransfer;

        foreach (explode('.', $propertyPath) as $key) {
            $current = $current->offsetGet($key);
        }

        return $current;
    }
}
