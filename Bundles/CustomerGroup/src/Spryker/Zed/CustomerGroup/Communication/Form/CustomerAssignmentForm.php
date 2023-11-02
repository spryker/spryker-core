<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Communication\Form;

use Generated\Shared\Transfer\CustomerGroupToCustomerAssignmentTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\CustomerGroup\Business\CustomerGroupFacadeInterface getFacade()
 * @method \Spryker\Zed\CustomerGroup\Communication\CustomerGroupCommunicationFactory getFactory()
 * @method \Spryker\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CustomerGroup\CustomerGroupConfig getConfig()
 * @method \Spryker\Zed\CustomerGroup\Persistence\CustomerGroupRepositoryInterface getRepository()
 */
class CustomerAssignmentForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_ID_CUSTOMER_GROUP = 'idCustomerGroup';

    /**
     * @var string
     */
    public const FIELD_IDS_CUSTOMER_TO_ASSIGN_CSV = 'idsCustomerToAssign';

    /**
     * @var string
     */
    public const FIELD_IDS_CUSTOMER_TO_DE_ASSIGN_CSV = 'idsCustomerToDeAssign';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => CustomerGroupToCustomerAssignmentTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addIdCustomerGroupField($builder)
            ->addIdsCustomerToAssignField($builder)
            ->addIdsCustomerToDeAssignField($builder);
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
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdsCustomerToAssignField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_IDS_CUSTOMER_TO_ASSIGN_CSV,
            HiddenType::class,
            [
                'attr' => [
                    'id' => 'js-items-to-assign-ids-csv-field',
                ],
            ],
        );

        $this->addIdsCsvModelTransformer(static::FIELD_IDS_CUSTOMER_TO_ASSIGN_CSV, $builder);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdsCustomerToDeAssignField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_IDS_CUSTOMER_TO_DE_ASSIGN_CSV,
            HiddenType::class,
            [
                'attr' => [
                    'id' => 'js-items-to-de-assign-ids-csv-field',
                ],
            ],
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
                    if (!$idsAsString) {
                        return [];
                    }

                    return explode(',', $idsAsString);
                },
            ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'customerAssignment';
    }

    /**
     * @deprecated Use {@link getBlockPrefix()} instead.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
