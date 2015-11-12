<?php

namespace SprykerFeature\Zed\Customer\Communication\Form;

use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Customer\Persistence\SpyCustomerAddressQuery;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CustomerTypeForm extends AbstractFormType
{
    const SALUTATION = 'salutation';
    const GENDER = 'gender';
    const ADD = 'add';
    const UPDATE = 'update';
    const PARAM_ID_CUSTOMER = 'id-customer';
    const FIELD_SEND_PASSWORD_TOKEN = 'send_password_token';
    const DEFAULT_BILLING_ADDRESS = 'default_billing_address';
    const DEFAULT_SHIPPING_ADDRESS = 'default_shipping_address';

    /**
     * @var SpyCustomerQuery
     */
    protected $customerQuery;

    /**
     * @var SpyCustomerAddressQuery
     */
    protected $customerAddressQuery;

    /**
     * @var string
     */
    protected $formType = '';

    /**
     * @var int
     */
    protected $idCustomer;

    /**
     * @param SpyCustomerQuery $customerQuery
     * @param SpyCustomerAddressQuery $customerAddressQuery
     * @param string $formType
     */
    public function __construct(
        SpyCustomerQuery $customerQuery,
        SpyCustomerAddressQuery $customerAddressQuery,
        $formType,
        $idCustomer
    )
    {
        $this->customerQuery = $customerQuery;
        $this->customerAddressQuery = $customerAddressQuery;
        $this->formType = $formType;
        $this->idCustomer = $idCustomer;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $emailParameters = [
            'label' => 'Email',
            'constraints' => $this->getEmailConstraints(),
        ];

        if (self::UPDATE === $this->formType) {
            $emailParameters['disabled'] = 'disabled';
        }

        $builder
            ->add('id_customer', 'hidden')
            ->add('email', 'email', $emailParameters)
            ->add(self::SALUTATION, 'choice', [
                'label' => 'Salutation',
                'placeholder' => 'Select one',
                'choices' => $this->getSalutationOptions(),
            ])
            ->add('first_name', 'text', [
                'label' => 'First Name',
                'constraints' => $this->getTextConstraints(),
            ])
            ->add('last_name', 'text', [
                'label' => 'Last Name',
                'constraints' => $this->getTextConstraints(),
            ])
            ->add(self::GENDER, 'choice', [
                'label' => 'Gender',
                'placeholder' => 'Select one',
                'choices' => $this->getGenderOptions(),
                'constraints' => [
                    $this->getConstraints()->createConstraintRequired(),
                ],
            ])
        ;

        if (self::UPDATE === $this->formType) {
            $builder
                ->add(self::DEFAULT_BILLING_ADDRESS, 'choice', [
                    'label' => 'Billing Address',
                    'placeholder' => 'Select one',
                    'choices' => $this->getAddressOptions(),
                ])
                ->add(self::DEFAULT_SHIPPING_ADDRESS, 'choice', [
                    'label' => 'Shipping Address',
                    'placeholder' => 'Select one',
                    'choices' => $this->getAddressOptions(),
                ])
            ;
        }

        $builder->add(self::FIELD_SEND_PASSWORD_TOKEN, 'checkbox', [
            'label' => 'Send password token through email',
        ]);
    }

    /**
     * @return array
     */
    protected function getAddressOptions()
    {
        $addresses = $this->customerAddressQuery->findByFkCustomer($this->idCustomer);

        $result = [];
        if (!empty($addresses)) {
            foreach ($addresses->getData() as $address) {
                $result[$address->getIdCustomerAddress()] = sprintf(
                    '%s %s (%s, %s %s)',
                    $address->getFirstName(),
                    $address->getLastName(),
                    $address->getAddress1(),
                    $address->getZipCode(),
                    $address->getCity()
                );
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    protected function getSalutationOptions()
    {
        $salutationSet = SpyCustomerTableMap::getValueSet(SpyCustomerTableMap::COL_SALUTATION);

        return $this->getEnumSet($salutationSet);
    }

    /**
     * @return array
     */
    protected function getGenderOptions()
    {
        $genderSet = SpyCustomerTableMap::getValueSet(SpyCustomerTableMap::COL_GENDER);

        return $this->getEnumSet($genderSet);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'customer';
    }

    /**
     * @return array
     */
    protected function getEmailConstraints()
    {
        $emailConstraints = [
            $this->getConstraints()->createConstraintNotBlank(),
            $this->getConstraints()->createConstraintRequired(),
            $this->getConstraints()->createConstraintEmail(),
        ];

        if (self::ADD === $this->formType) {
            $emailConstraints[] = $this->getConstraints()->createConstraintCallback([
                'methods' => [
                    function ($email, ExecutionContextInterface $context) {
                        if ($this->customerQuery->findByEmail($email)->count() > 0) {
                            $context->addViolation('Email is already used');
                        }
                    },
                ],
            ]);

            return $emailConstraints;
        }

        return $emailConstraints;
    }

    /**
     * @return array
     */
    protected function getTextConstraints()
    {
        return [
            $this->getConstraints()->createConstraintRequired(),
            $this->getConstraints()->createConstraintNotBlank(),
            $this->getConstraints()->createConstraintLength(['max' => 100]),
        ];
    }

}
