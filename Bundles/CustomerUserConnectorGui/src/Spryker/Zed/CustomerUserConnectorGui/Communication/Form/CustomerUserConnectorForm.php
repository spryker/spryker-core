<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerUserConnectorGui\Communication\Form;

use Generated\Shared\Transfer\CustomerUserConnectionTransfer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerUserConnectorForm extends AbstractType
{

    const FIELD_ID_USER = CustomerUserConnectionTransfer::ID_USER;
    const FIELD_IDS_USER_TO_ASSIGN_CSV = CustomerUserConnectionTransfer::ID_CUSTOMERS_TO_ASSIGN;
    const FIELD_IDS_USER_TO_DE_ASSIGN_CSV = CustomerUserConnectionTransfer::ID_CUSTOMERS_TO_DE_ASSIGN;

    /**
     * @return string
     */
    public function getName()
    {
        return 'customerUserConnection';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CustomerUserConnectionTransfer::class,
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
            static::FIELD_ID_USER,
            HiddenType::class
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
            static::FIELD_IDS_USER_TO_ASSIGN_CSV,
            HiddenType::class,
            [
                'attr' => [
                    'id' => 'js-users-to-assign-ids-csv-field',
                ],
            ]
        );

        $this->addIdsCsvModelTransformer(static::FIELD_IDS_USER_TO_ASSIGN_CSV, $builder);

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
            static::FIELD_IDS_USER_TO_DE_ASSIGN_CSV,
            HiddenType::class,
            [
                'attr' => [
                    'id' => 'js-users-to-de-assign-ids-csv-field',
                ],
            ]
        );

        $this->addIdsCsvModelTransformer(static::FIELD_IDS_USER_TO_DE_ASSIGN_CSV, $builder);

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
                function ($idsProductAbstractAsArray) {
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

}
