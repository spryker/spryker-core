<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Communication\Form;

use Generated\Shared\Transfer\CustomerGroupTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\CustomerGroup\Communication\Form\CustomerAssignmentForm;
use Spryker\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CustomerGroupForm extends AbstractType
{

    const FIELD_NAME = 'name';
    const FIELD_DESCRIPTION = 'description';
    const FIELD_ID_CUSTOMER_GROUP = 'idCustomerGroup';
    const FIELD_CUSTOMER_ASSIGNMENT = 'customerAssignment';

    /**
     * @var \Spryker\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface
     */
    protected $customerGroupQueryContainer;

    /**
     * @var \Spryker\Zed\CustomerGroup\Communication\Form\CustomerAssignmentForm
     */
    protected $customerAssignmentForm;

    /**
     * @var int|null
     */
    private $idCustomerGroup;

    /**
     * @param \Spryker\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface $customerGroupQueryContainer
     * @param \Spryker\Zed\CustomerGroup\Communication\Form\CustomerAssignmentForm $customerAssignmentForm
     * @param int|null $idCustomerGroup
     */
    public function __construct(
        CustomerGroupQueryContainerInterface $customerGroupQueryContainer,
        CustomerAssignmentForm $customerAssignmentForm,
        $idCustomerGroup
    ) {
        $this->customerGroupQueryContainer = $customerGroupQueryContainer;
        $this->customerAssignmentForm = $customerAssignmentForm;
        $this->idCustomerGroup = $idCustomerGroup;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'customer_group';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CustomerGroupTransfer::class,
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
            ->addNameField($builder)
            ->addDescriptionField($builder)
            ->addCustomerAssignmentSubForm($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdCustomerGroupField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_CUSTOMER_GROUP, 'hidden');

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_NAME, 'text', [
            'label' => 'Name',
            'constraints' => $this->getNameFieldConstraints(),
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDescriptionField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_DESCRIPTION, 'textarea', [
            'label' => 'Description',
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @return array
     */
    protected function getNameFieldConstraints()
    {
        $constraints = [
            new Required(),
            new NotBlank(),
            new Length(['max' => 70]),
        ];

        $customerGroupQuery = $this->customerGroupQueryContainer->queryCustomerGroup();
        if ($this->idCustomerGroup) {
            $customerGroupQuery->filterByIdCustomerGroup($this->idCustomerGroup, Criteria::NOT_EQUAL);
        }

        $constraints[] = new Callback([
            'methods' => [
                function ($name, ExecutionContextInterface $context) use ($customerGroupQuery) {
                    if ($customerGroupQuery->findByName($name)->count() > 0) {
                        $context->addViolation('Name is already used');
                    }
                },
            ],
        ]);

        return $constraints;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCustomerAssignmentSubForm(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_CUSTOMER_ASSIGNMENT,
            $this->customerAssignmentForm,
            [
                'label' => false,
            ]
        );

        return $this;
    }

}
