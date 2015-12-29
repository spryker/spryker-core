<?php

namespace Spryker\Zed\Sales\Communication\Form;

use Orm\Zed\Sales\Persistence\Base\SpySalesOrderQuery;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Spryker\Shared\Gui\Form\AbstractForm;
use Symfony\Component\Form\FormBuilderInterface;

class CustomerForm extends AbstractForm
{

    const FIRST_NAME = 'first_name';
    const LAST_NAME = 'last_name';
    const SALUTATION = 'salutation';
    const EMAIL = 'email';
    const SUBMIT = 'submit';

    protected $orderQuery;

    protected $idOrder;

    /**
     * @param SpySalesOrderQuery $orderQuery
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
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::SALUTATION, 'choice', [
                'label' => 'Salutation',
                'placeholder' => '-select-',
                'choices' => $this->getSalutationOptions(),
            ])
            ->add(self::FIRST_NAME, 'text', [
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                ],
            ])
            ->add(self::LAST_NAME, 'text', [
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                ],
            ])
            ->add(self::EMAIL, 'text', [
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
            self::FIRST_NAME => $order->getFirstName(),
            self::LAST_NAME => $order->getLastName(),
            self::SALUTATION => $order->getSalutation(),
            self::EMAIL => $order->getEmail(),
        ];
    }

}
