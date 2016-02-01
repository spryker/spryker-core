<?php

namespace Spryker\Zed\Sales\Communication\Form;

use Orm\Zed\Sales\Persistence\Base\SpySalesOrderQuery;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Spryker\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Form\FormBuilderInterface;

class CustomerForm extends AbstractForm
{

    const FIELD_FIRST_NAME = 'first_name';
    const FIELD_LAST_NAME = 'last_name';
    const FIELD_SALUTATION = 'salutation';
    const FIELD_EMAIL = 'email';
    const SUBMIT = 'submit';

    protected $orderQuery;

    protected $idOrder;

    /**
     * @param \Orm\Zed\Sales\Persistence\Base\SpySalesOrderQuery $orderQuery
     */
    public function __construct(SpySalesOrderQuery $orderQuery)
    {
        $this->orderQuery = $orderQuery;
    }

    /**
     * @return null
     */
    protected function getDataClass()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'customer';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::FIELD_SALUTATION, 'choice', [
                'label' => 'Salutation',
                'placeholder' => '-select-',
                'choices' => $this->getSalutationOptions(),
            ])
            ->add(self::FIELD_FIRST_NAME, 'text', [
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                ],
            ])
            ->add(self::FIELD_LAST_NAME, 'text', [
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                ],
            ])
            ->add(self::FIELD_EMAIL, 'text', [
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                ],
            ]);
    }

    /**
     * @return array
     */
    protected function getSalutationOptions()
    {
        return [
            SpyCustomerTableMap::COL_SALUTATION_MR => SpyCustomerTableMap::COL_SALUTATION_MR,
            SpyCustomerTableMap::COL_SALUTATION_MRS => SpyCustomerTableMap::COL_SALUTATION_MRS,
            SpyCustomerTableMap::COL_SALUTATION_DR => SpyCustomerTableMap::COL_SALUTATION_DR,
        ];
    }

    /**
     * @return array
     */
    public function populateFormFields()
    {
        $order = $this->orderQuery->findOne();

        return [
            self::FIELD_FIRST_NAME => $order->getFirstName(),
            self::FIELD_LAST_NAME => $order->getLastName(),
            self::FIELD_SALUTATION => $order->getSalutation(),
            self::FIELD_EMAIL => $order->getEmail(),
        ];
    }

}
