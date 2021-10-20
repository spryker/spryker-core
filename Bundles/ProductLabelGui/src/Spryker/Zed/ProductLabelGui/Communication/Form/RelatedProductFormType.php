<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Form;

use Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\ProductLabelGui\Communication\ProductLabelGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductLabelGui\Persistence\ProductLabelGuiQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductLabelGui\ProductLabelGuiConfig getConfig()
 */
class RelatedProductFormType extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_ID_PRODUCT_LABEL = 'idProductLabel';

    /**
     * @var string
     */
    public const FIELD_IDS_PRODUCT_ABSTRACT_CSV = 'idsProductAbstractCsv';

    /**
     * @var string
     */
    public const FIELD_IDS_PRODUCT_ABSTRACT_TO_ASSIGN_CSV = 'idsProductAbstractToAssignCsv';

    /**
     * @var string
     */
    public const FIELD_IDS_PRODUCT_ABSTRACT_TO_DE_ASSIGN_CSV = 'idsProductAbstractToDeAssignCsv';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductLabelProductAbstractRelationsTransfer::class,
            'constraints' => [
                $this->getFactory()->createAddIdsProductToAssignConstraint(),
                $this->getFactory()->createAddIdsProductToDeAssignConstraint(),
            ],
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
            ->addIdsProductAbstractToAssignCsvField($builder)
            ->addIdsProductAbstractToDeAssignCsvField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductLabelIdField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_ID_PRODUCT_LABEL,
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
    protected function addIdsProductAbstractToAssignCsvField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_IDS_PRODUCT_ABSTRACT_TO_ASSIGN_CSV,
            HiddenType::class,
            [
                'property_path' => 'idsProductAbstractToAssign',
                'attr' => [
                    'id' => 'js-abstract-products-to-assign-ids-csv-field',
                ],
            ]
        );

        $this->addIdsCsvModelTransformer(static::FIELD_IDS_PRODUCT_ABSTRACT_TO_ASSIGN_CSV, $builder);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdsProductAbstractToDeAssignCsvField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_IDS_PRODUCT_ABSTRACT_TO_DE_ASSIGN_CSV,
            HiddenType::class,
            [
                'property_path' => 'idsProductAbstractToDeAssign',
                'attr' => [
                    'id' => 'js-abstract-products-to-de-assign-ids-csv-field',
                ],
            ]
        );

        $this->addIdsCsvModelTransformer(static::FIELD_IDS_PRODUCT_ABSTRACT_TO_DE_ASSIGN_CSV, $builder);

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
                function (array $idsProductAbstractAsArray) {
                    if (!count($idsProductAbstractAsArray)) {
                        return [];
                    }

                    return implode(',', $idsProductAbstractAsArray);
                },
                function ($idsProductAbstractAsCsv) {
                    if (empty($idsProductAbstractAsCsv)) {
                        return [];
                    }

                    return explode(',', $idsProductAbstractAsCsv);
                }
            ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'productRelation';
    }
}
