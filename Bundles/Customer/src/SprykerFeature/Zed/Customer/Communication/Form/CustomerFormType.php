<?php

namespace SprykerFeature\Zed\Customer\Communication\Form;

use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use SprykerFeature\Zed\Customer\Persistence\CustomerQueryContainerInterface;
use SprykerEngine\Zed\Gui\Communication\Form\AbstractFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CustomerFormType extends AbstractFormType
{

    const ADD = 'add';
    const UPDATE = 'update';
    const PARAM_ID_CUSTOMER = 'id-customer';

    const FIELD_SALUTATION = 'salutation';
    const FIELD_GENDER = 'gender';
    const FIELD_SEND_PASSWORD_TOKEN = 'send_password_token';
    const FIELD_ID_CUSTOMER = 'id_customer';
    const FIELD_EMAIL = 'email';
    const FIELD_DEFAULT_BILLING_ADDRESS = 'default_billing_address';
    const FIELD_DEFAULT_SHIPPING_ADDRESS = 'default_shipping_address';
    const FIELD_FIRST_NAME = 'first_name';
    const FIELD_LAST_NAME = 'last_name';

    /**
     * @var string
     */
    protected $addOrUpdate;

    /**
     * @var CustomerQueryContainerInterface
     */
    protected $customerQueryContainer;

    /**
     * CustomerFormType constructor.
     *
     * @param CustomerQueryContainerInterface $customerQueryContainer
     * @param string $addOrUpdate
     */
    public function __construct(CustomerQueryContainerInterface $customerQueryContainer, $addOrUpdate)
    {
        $this->customerQueryContainer = $customerQueryContainer;
        $this->addOrUpdate = $addOrUpdate;
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

        if ($this->addOrUpdate === self::UPDATE) {
            $emailParameters['disabled'] = 'disabled';
        }

        $builder
            ->add(self::FIELD_ID_CUSTOMER, 'hidden')
            ->add(self::FIELD_EMAIL, self::FIELD_EMAIL, $emailParameters)
            ->add(self::FIELD_SALUTATION, 'choice', [
                'label' => 'Salutation',
                'placeholder' => 'Select one',
                'choices' => $this->getSalutationOptions(),
            ])
            ->add(self::FIELD_FIRST_NAME, 'text', [
                'label' => 'First Name',
                'constraints' => $this->getTextConstraints(),
            ])
            ->add(self::FIELD_LAST_NAME, 'text', [
                'label' => 'Last Name',
                'constraints' => $this->getTextConstraints(),
            ])
            ->add(self::FIELD_GENDER, 'choice', [
                'label' => 'Gender',
                'placeholder' => 'Select one',
                'choices' => $this->getGenderOptions(),
                'constraints' => [
                    $this->getConstraints()->createConstraintRequired(),
                ],
            ]);

        if ($this->addOrUpdate === self::UPDATE) {
            $builder
                ->add(self::FIELD_DEFAULT_BILLING_ADDRESS, 'choice', [
                    'label' => 'Billing Address',
                    'placeholder' => 'Select one',
                    'choices' => $this->getAddressOptions(),
                ])
                ->add(self::FIELD_DEFAULT_SHIPPING_ADDRESS, 'choice', [
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
        $addresses = $this->customerQueryContainer->queryAddressByIdCustomer($this->getIdCustomer())->find();

        $result = [];
        if (!empty($addresses)) {
            foreach ($addresses as $address) {
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
     * return int
     */
    protected function getIdCustomer()
    {
        return $this->getRequest()->query->get('id-customer');
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

        if (self::ADD === $this->addOrUpdate) {
            $customerQuery = $this->customerQueryContainer->queryCustomers();

            $emailConstraints[] = $this->getConstraints()->createConstraintCallback([
                'methods' => [
                    function ($email, ExecutionContextInterface $context) use ($customerQuery) {
                        if ($customerQuery->findByEmail($email)->count() > 0) {
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
