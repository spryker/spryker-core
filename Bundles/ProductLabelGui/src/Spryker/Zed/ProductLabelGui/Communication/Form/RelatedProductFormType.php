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
            ->addAbstractProductIdsCsvField($builder);
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
    protected function addAbstractProductIdsCsvField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_ABSTRACT_PRODUCT_IDS_CSV,
            HiddenType::class,
            [
                'property_path' => 'abstractProductIds',
                'attr' => [
                    'id' => 'js-abstract-product-ids-csv-field',
                ],
            ]
        );

        $this->addAbstractProductIdsModelTransformer($builder);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addAbstractProductIdsModelTransformer(FormBuilderInterface $builder)
    {
        $builder
            ->get(static::FIELD_ABSTRACT_PRODUCT_IDS_CSV)
            ->addModelTransformer(new CallbackTransformer(
                function (array $abstractProductIdsAsArray) {
                    return implode(',', $abstractProductIdsAsArray);
                },
                function ($abstractProductIdsAsCsv) {
                    return explode(',', $abstractProductIdsAsCsv);
                }
            ));
    }

}
