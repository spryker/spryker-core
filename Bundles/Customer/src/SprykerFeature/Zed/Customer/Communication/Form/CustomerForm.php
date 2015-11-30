<?php

namespace SprykerFeature\Zed\Customer\Communication\Form;

use Generated\Shared\Transfer\CustomerTransfer;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Pyz\Zed\Customer\CustomerConfig;
use SprykerEngine\Shared\Transfer\TransferInterface;
use SprykerFeature\Zed\Customer\Persistence\CustomerQueryContainerInterface;
use SprykerEngine\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CustomerForm extends AbstractForm
{

    const ADD = 'add';
    const UPDATE = 'update';

    /**
     * @var string
     */
    protected $formActionType;

    /**
     * @var CustomerQueryContainerInterface
     */
    protected $customerQueryContainer;

    /**
     * @param CustomerQueryContainerInterface $customerQueryContainerInterface
     * @param string $formActionType
     */
    public function __construct(CustomerQueryContainerInterface $customerQueryContainerInterface, $formActionType)
    {
        $this->customerQueryContainer = $customerQueryContainerInterface;
        $this->formActionType = $formActionType;
    }

    /**
     * @return TransferInterface
     */
    public function populateFormFields()
    {
        $idCustomer = $this->getRequest()->query->getInt(CustomerConfig::PARAM_ID_CUSTOMER);
        $customerTransfer = $this->getDataClass();

        if (empty($idCustomer)) {
            return $customerTransfer;
        }

        $customerEntity = $this
            ->customerQueryContainer
            ->queryCustomerById($idCustomer)
            ->findOne();

        return $customerTransfer->fromArray($customerEntity->toArray(), true);
    }

    /**
     * @return CustomerTransfer
     */
    protected function getDataClass()
    {
        return new CustomerTransfer();
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

        if ($this->formActionType === self::UPDATE) {
            $emailParameters['disabled'] = 'disabled';
        }

        $builder
            ->add(CustomerTransfer::ID_CUSTOMER, 'hidden')
            ->add(CustomerTransfer::EMAIL, 'email', $emailParameters)
            ->add(CustomerTransfer::SALUTATION, 'choice', [
                'label' => 'Salutation',
                'placeholder' => 'Select one',
                'choices' => $this->getSalutationOptions(),
            ])
            ->add(CustomerTransfer::FIRST_NAME, 'text', [
                'label' => 'First Name',
                'constraints' => $this->getTextConstraints(),
            ])
            ->add(CustomerTransfer::LAST_NAME, 'text', [
                'label' => 'Last Name',
                'constraints' => $this->getTextConstraints(),
            ])
            ->add(CustomerTransfer::GENDER, 'choice', [
                'label' => 'Gender',
                'placeholder' => 'Select one',
                'choices' => $this->getGenderOptions(),
                'constraints' => [
                    $this->getConstraints()->createConstraintRequired(),
                ],
            ]);

        if ($this->formActionType === self::UPDATE) {
            $builder
                ->add(CustomerTransfer::DEFAULT_BILLING_ADDRESS, 'choice', [
                    'label' => 'Billing Address',
                    'placeholder' => 'Select one',
                    'choices' => $this->getAddressOptions(),
                ])
                ->add(CustomerTransfer::DEFAULT_SHIPPING_ADDRESS, 'choice', [
                    'label' => 'Shipping Address',
                    'placeholder' => 'Select one',
                    'choices' => $this->getAddressOptions(),
                ]);
        }

        $builder->add(CustomerTransfer::SEND_PASSWORD_TOKEN, 'checkbox', [
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

        if ($this->formActionType === self::ADD) {
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
