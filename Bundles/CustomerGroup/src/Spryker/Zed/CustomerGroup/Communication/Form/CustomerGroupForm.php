<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Communication\Form;

use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\ArrayCollection;
use Spryker\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CustomerGroupForm extends AbstractType
{

    const FIELD_NAME = 'name';
    const FIELD_DESCRIPTION = 'description';
    const FIELD_ID_CUSTOMER_GROUP = 'id_customer_group';
    const FIELD_CUSTOMERS = 'customers';

    /**
     * @var \Spryker\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface
     */
    protected $customerGroupQueryContainer;

    /**
     * @var int|null
     */
    private $idCustomerGroup;

    /**
     * @param \Spryker\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface $customerGroupQueryContainer
     * @param int|null $idCustomerGroup
     */
    public function __construct(CustomerGroupQueryContainerInterface $customerGroupQueryContainer, $idCustomerGroup)
    {
        $this->customerGroupQueryContainer = $customerGroupQueryContainer;
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
            ->addCustomersField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdCustomerGroupField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_ID_CUSTOMER_GROUP, 'hidden');

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_NAME, 'text', [
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
        $builder->add(self::FIELD_DESCRIPTION, 'textarea', [
            'label' => 'Description',
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCustomersField(FormBuilderInterface $builder)
    {
        $customerCollection = $this->customerGroupQueryContainer->queryCustomer()
            ->select([
                 SpyCustomerTableMap::COL_ID_CUSTOMER,
                 SpyCustomerTableMap::COL_FIRST_NAME,
                 SpyCustomerTableMap::COL_LAST_NAME,
                 SpyCustomerTableMap::COL_EMAIL,
            ])
            ->find();

        $choices = $this->buildCustomerChoiceList($customerCollection);

        $builder->add(self::FIELD_CUSTOMERS, new Select2ComboBoxType(), [
            'label' => 'Assigned Users',
            'placeholder' => false,
            'multiple' => true,
            'choices' => $choices,
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
     * @param \Propel\Runtime\Collection\ArrayCollection|\Orm\Zed\Customer\Persistence\SpyCustomer[] $customerCollection
     *
     * @return array
     */
    protected function buildCustomerChoiceList(ArrayCollection $customerCollection)
    {
        $customerChoiceList = [];
        foreach ($customerCollection as $customerEntity) {
            $customerChoiceList[$customerEntity[SpyCustomerTableMap::COL_ID_CUSTOMER]] = sprintf(
                '%s %s (%s)',
                $customerEntity[SpyCustomerTableMap::COL_FIRST_NAME],
                $customerEntity[SpyCustomerTableMap::COL_LAST_NAME],
                $customerEntity[SpyCustomerTableMap::COL_EMAIL]
            );
        }
        return $customerChoiceList;
    }

}
