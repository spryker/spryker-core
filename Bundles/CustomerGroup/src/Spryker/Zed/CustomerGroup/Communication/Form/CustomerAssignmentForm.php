<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Communication\Form;

use Generated\Shared\Transfer\CustomerGroupToCustomerAssignmentTransfer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerAssignmentForm extends AbstractType
{

    const FIELD_ID_CUSTOMER_GROUP = 'idCustomerGroup';
    const FIELD_IDS_CUSTOMER_TO_ASSIGN_CSV = 'idsCustomerToAssignCsv';
    const FIELD_IDS_CUSTOMER_TO_DE_ASSIGN_CSV = 'idsCustomerToDeAssignCsv';

    /**
     * @return string
     */
    public function getName()
    {
        return 'customerAssignment';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => CustomerGroupToCustomerAssignmentTransfer::class,
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
            ->addIdCustomerGroupField($builder)
            ->addIdsCustomerToAssignCsvField($builder)
            ->addIdsCustomerToDeAssignCsvField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdCustomerGroupField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_ID_CUSTOMER_GROUP,
            HiddenType::class,
            [
                'property_path' => 'idCustomerGroup',
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdsCustomerToAssignCsvField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_IDS_CUSTOMER_TO_ASSIGN_CSV,
            HiddenType::class,
            [
                'property_path' => CustomerGroupToCustomerAssignmentTransfer::IDS_CUSTOMER_TO_ASSIGN,
                'attr' => [
                    'id' => 'js-items-to-assign-ids-csv-field',
                ],
            ]
        );

        $this->addIdsCsvModelTransformer(static::FIELD_IDS_CUSTOMER_TO_ASSIGN_CSV, $builder);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdsCustomerToDeAssignCsvField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_IDS_CUSTOMER_TO_DE_ASSIGN_CSV,
            HiddenType::class,
            [
                'property_path' => CustomerGroupToCustomerAssignmentTransfer::IDS_CUSTOMER_TO_DE_ASSIGN,
                'attr' => [
                    'id' => 'js-items-to-de-assign-ids-csv-field',
                ],
            ]
        );

        $this->addIdsCsvModelTransformer(static::FIELD_IDS_CUSTOMER_TO_DE_ASSIGN_CSV, $builder);

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
                function (array $idsAsArray) {
                    if (!count($idsAsArray)) {
                        return [];
                    }

                    return implode(',', $idsAsArray);
                },
                function ($idsAsString) {
                    if (empty($idsAsString)) {
                        return [];
                    }

                    return explode(',', $idsAsString);
                }
            ));
    }

}
