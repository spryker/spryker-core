<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication\Form;

use Orm\Zed\Customer\Persistence\Base\SpyCustomerAddressQuery;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Validator\Context\ExecutionContext;

class CustomerForm extends AbstractForm
{

    const ADD = 'add';
    const UPDATE = 'update';
    const SALUTATION = 'salutation';
    const GENDER = 'gender';
    const ID_CUSTOMER = 'id_customer';
    const FIELD_SEND_PASSWORD_TOKEN = 'send_password_token';

    /**
     * @var SpyCustomerQuery
     */
    protected $customerQuery;

    /**
     * @var SpyCustomerAddressQuery
     */
    protected $customerAddressQuery;

    /**
     * @var
     */
    protected $type;

    /**
     * @param SpyCustomerQuery $customerQuery
     * @param SpyCustomerAddressQuery $customerAddressQuery
     * @param string $type
     */
    public function __construct(SpyCustomerQuery $customerQuery, SpyCustomerAddressQuery $customerAddressQuery, $type)
    {
        $this->customerQuery = $customerQuery;
        $this->customerAddressQuery = $customerAddressQuery;
        $this->type = $type;
    }

    /**
     * @return self
     */
    public function buildFormFields()
    {
        $emailConstraints = [
            $this->getConstraints()->createConstraintNotBlank(),
            $this->getConstraints()->createConstraintRequired(),
            $this->getConstraints()->createConstraintEmail(),
        ];

        if (self::ADD === $this->type) {
            $emailConstraints[] = $this->getConstraints()->createConstraintCallback([
                'methods' => [
                    function ($email, ExecutionContext $context) {
                        if ($this->customerQuery->findByEmail($email)
                                ->count() > 0
                        ) {
                            $context->addViolation('Email is already used');
                        }
                    },
                ],
            ]);
        }

        $emailParams = [
            'label' => 'Email',
            'constraints' => $emailConstraints,
        ];

        if (self::UPDATE === $this->type) {
            $emailParams['disabled'] = 'disabled';
        }

        $this->addHidden(self::ID_CUSTOMER, [
            'label' => 'Customer ID',
        ])
            ->addEmail('email', $emailParams)
            ->addChoice(self::SALUTATION, [
                'label' => 'Salutation',
                'placeholder' => 'Select one',
                'choices' => $this->getSalutationOptions(),
            ])
            ->addText('first_name', [
                'label' => 'First Name',
                'constraints' => [
                    $this->getConstraints()->createConstraintRequired(),
                    $this->getConstraints()->createConstraintNotBlank(),
                    $this->getConstraints()->createConstraintLength(['max' => 100]),
                ],
            ])
            ->addText('last_name', [
                'label' => 'Last Name',
                'constraints' => [
                    $this->getConstraints()->createConstraintRequired(),
                    $this->getConstraints()->createConstraintNotBlank(),
                    $this->getConstraints()->createConstraintLength(['max' => 100]),
                ],
            ])
            ->addChoice(self::GENDER, [
                'label' => 'Gender',
                'placeholder' => 'Select one',
                'choices' => $this->getGenderOptions(),
                'constraints' => [
                    $this->getConstraints()->createConstraintRequired(),
                ],
            ])
        ;

        if (self::UPDATE === $this->type) {
            $this->addChoice('default_billing_address', [
                'label' => 'Billing Address',
                'placeholder' => 'Select one',
                'choices' => $this->getAddressOptions(),
            ])
                ->addChoice('default_shipping_address', [
                    'label' => 'Shipping Address',
                    'placeholder' => 'Select one',
                    'choices' => $this->getAddressOptions(),
                ])
            ;
        }

        $this->addCheckbox(self::FIELD_SEND_PASSWORD_TOKEN, [
            'label' => 'Send password token through email',
        ]);

        return $this;
    }

    /**
     * @return array
     */
    protected function populateFormFields()
    {
        $result = [];

        $idCustomer = $this->request->get(self::ID_CUSTOMER);

        if ($idCustomer !== null) {
            $customerDetailEntity = $this->customerQuery->findOneByIdCustomer($idCustomer);

            if ($customerDetailEntity !== null) {
                $result = $customerDetailEntity->toArray();
            }
        }

        if (false === empty($result[self::SALUTATION])) {
            $salutations = array_flip($this->getSalutationOptions());

            if (true === isset($salutations[$result[self::SALUTATION]])) {
                $result[self::SALUTATION] = $salutations[$result[self::SALUTATION]];
            }
        }

        if (false === empty($result[self::GENDER])) {
            $genders = array_flip($this->getGenderOptions());

            if (true === isset($genders[$result[self::GENDER]])) {
                $result[self::GENDER] = $genders[$result[self::GENDER]];
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    protected function getGenderOptions()
    {
        return SpyCustomerTableMap::getValueSet(SpyCustomerTableMap::COL_GENDER);
    }

    /**
     * @return array
     */
    protected function getSalutationOptions()
    {
        return SpyCustomerTableMap::getValueSet(SpyCustomerTableMap::COL_SALUTATION);
    }

    /**
     * @return array
     */
    protected function getAddressOptions()
    {
        $idCustomer = $this->request->get(self::ID_CUSTOMER);
        $addresses = $this->customerAddressQuery->findByFkCustomer($idCustomer);

        $result = [];
        if (false === empty($addresses)) {
            foreach ($addresses->getData() as $address) {
                $result[$address->getIdCustomerAddress()] = sprintf('%s %s (%s, %s %s)', $address->getFirstName(), $address->getLastName(), $address->getAddress1(), $address->getZipCode(), $address->getCity());
            }
        }

        return $result;
    }

}
