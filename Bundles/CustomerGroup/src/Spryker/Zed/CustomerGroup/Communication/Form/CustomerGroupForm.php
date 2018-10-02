<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Communication\Form;

use Generated\Shared\Transfer\CustomerGroupToCustomerAssignmentTransfer;
use Generated\Shared\Transfer\CustomerGroupTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\CustomerGroup\Business\CustomerGroupFacadeInterface getFacade()
 * @method \Spryker\Zed\CustomerGroup\Communication\CustomerGroupCommunicationFactory getFactory()
 * @method \Spryker\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface getQueryContainer()
 */
class CustomerGroupForm extends AbstractType
{
    public const FIELD_NAME = 'name';
    public const FIELD_DESCRIPTION = 'description';
    public const FIELD_ID_CUSTOMER_GROUP = self::ID_CUSTOMER_GROUP;
    public const FIELD_CUSTOMER_ASSIGNMENT = 'customerAssignment';
    public const ID_CUSTOMER_GROUP = 'idCustomerGroup';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(static::ID_CUSTOMER_GROUP);
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
            ->addNameField($builder, $options)
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
        $builder->add(static::FIELD_ID_CUSTOMER_GROUP, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_NAME, TextType::class, [
            'label' => 'Name',
            'constraints' => $this->getNameFieldConstraints($options),
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
        $builder->add(static::FIELD_DESCRIPTION, TextareaType::class, [
            'label' => 'Description',
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param array $options
     *
     * @return array
     */
    protected function getNameFieldConstraints(array $options)
    {
        $constraints = [
            new Required(),
            new NotBlank(),
            new Length(['max' => 70]),
        ];

        $customerGroupQuery = $this->getQueryContainer()->queryCustomerGroup();
        if ($options[static::ID_CUSTOMER_GROUP]) {
            $customerGroupQuery->filterByIdCustomerGroup($options[static::ID_CUSTOMER_GROUP], Criteria::NOT_EQUAL);
        }

        $constraints[] = new Callback([
            'callback' => function ($name, ExecutionContextInterface $context) use ($customerGroupQuery) {
                if ($customerGroupQuery->findByName($name)->count() > 0) {
                    $context->addViolation('Name is already used');
                }
            },
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
            CustomerAssignmentForm::class,
            [
                'label' => false,
                'data_class' => CustomerGroupToCustomerAssignmentTransfer::class,
            ]
        );

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'customer_group';
    }

    /**
     * @deprecated Use `getBlockPrefix()` instead.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
