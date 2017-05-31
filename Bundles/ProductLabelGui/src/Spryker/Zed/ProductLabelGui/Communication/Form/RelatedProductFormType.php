<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Form;

use Generated\Shared\Transfer\ProductLabelAbstractProductRelationsTransfer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RelatedProductFormType extends AbstractType
{

    const FIELD_PRODUCT_LABEL_ID = 'productLabelId';
    const FIELD_ABSTRACT_PRODUCT_IDS_CSV = 'abstractProductIdsCsv';
    const FIELD_ABSTRACT_PRODUCTS_TO_ASSIGN_IDS_CSV = 'abstractProductsToAssignIdsCsv';
    const FIELD_ABSTRACT_PRODUCTS_TO_DE_ASSIGN_IDS_CSV = 'abstractProductsToDeAssignIdsCsv';

    /**
     * @return string
     */
    public function getName()
    {
        return 'productRelation';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductLabelAbstractProductRelationsTransfer::class,
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
            ->addProductLabelIdField($builder)
            ->addAbstractProductsToAssignIdsCsvField($builder)
            ->addAbstractProductsToDeAssignIdsCsvField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductLabelIdField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_PRODUCT_LABEL_ID,
            HiddenType::class,
            [
                'property_path' => 'idProductLabel',
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAbstractProductsToAssignIdsCsvField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_ABSTRACT_PRODUCTS_TO_ASSIGN_IDS_CSV,
            HiddenType::class,
            [
                'property_path' => 'abstractProductIdsToAssign',
                'attr' => [
                    'id' => 'js-abstract-products-to-assign-ids-csv-field',
                ],
            ]
        );

        $this->addIdsCsvModelTransformer(static::FIELD_ABSTRACT_PRODUCTS_TO_ASSIGN_IDS_CSV, $builder);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAbstractProductsToDeAssignIdsCsvField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_ABSTRACT_PRODUCTS_TO_DE_ASSIGN_IDS_CSV,
            HiddenType::class,
            [
                'property_path' => 'abstractProductIdsToDeAssign',
                'attr' => [
                    'id' => 'js-abstract-products-to-de-assign-ids-csv-field',
                ],
            ]
        );

        $this->addIdsCsvModelTransformer(static::FIELD_ABSTRACT_PRODUCTS_TO_DE_ASSIGN_IDS_CSV, $builder);

        return $this;
    }

    /**
     * @param string $fieldName
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addIdsCsvModelTransformer($fieldName, FormBuilderInterface $builder)
    {
        $builder
            ->get($fieldName)
            ->addModelTransformer(new CallbackTransformer(
                function (array $abstractProductIdsAsArray) {
                    if (!count($abstractProductIdsAsArray)) {
                        return [];
                    }

                    return implode(',', $abstractProductIdsAsArray);
                },
                function ($abstractProductIdsAsCsv) {
                    if (empty($abstractProductIdsAsCsv)) {
                        return [];
                    }

                    return explode(',', $abstractProductIdsAsCsv);
                }
            ));
    }

}
