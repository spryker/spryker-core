<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication\Form;

use Generated\Shared\Transfer\CustomerTransfer;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomerQuery;
use SprykerFeature\Zed\Customer\Persistence\Propel\Map\SpyCustomerTableMap;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Callback;
use SprykerFeature\Zed\Customer\Business\Customer\Customer;
use Symfony\Component\Validator\Context\ExecutionContext;
use SprykerFeature\Zed\Customer\Business\CustomerFacade;

class CustomerForm extends AbstractForm
{
    const ADD = 'add';
    const UPDATE = 'update';

    /**
     * @var SpyCustomerQueryQuery
     */
    protected $customerQuery;

    /**
     * @var
     */
    protected $type;

    /**
     * @param SpyCustomerQuery $customerQuery
     */
    public function __construct(SpyCustomerQuery $customerQuery, $type)
    {
        $this->customerQuery = $customerQuery;
        $this->type = $type;
    }

    /**
     * @return $this
     */
    public function buildFormFields()
    {
        $this->addHidden(
            'IdCustomer',
            [
                'label' => 'Customer ID',
            ]
        )
            ->addEmail(
                'email',
                [
                    'label'       => 'Email',
                    'constraints' => [
                        new NotBlank(),
                        new Required(),
                        new Email(),
                        new Callback([
                            'methods' => [
                                function ($email, ExecutionContext $context)
                                {
                                    if ($this->getCustomerFacade()->hasEmail($email))
                                    {
                                        $context->addViolation('Email is already used');
                                    }
                                },
                            ],
                        ]),
                    ],
                ]
            )
            ->addChoice(
                'salutation',
                [
                    'label'       => 'Salutation',
                    'placeholder' => 'Select one',
                    'choices'     => $this->getSalutationOptions(),
                ]
            )
            ->addText(
                'first_name',
                [
                    'label'       => 'First Name',
                    'constraints' => [
                        new Required(),
                        new NotBlank(),
                        new Length(['max' => 100]),
                    ],
                ]
            )
            ->addText(
                'last_name',
                [
                    'label'       => 'Last Name',
                    'constraints' => [
                        new Required(),
                        new NotBlank(),
                        new Length(['max' => 100]),
                    ],
                ]
            )
            ->addChoice(
                'gender',
                [
                    'label'       => 'Gender',
                    'placeholder' => 'Select one',
                    'choices'     => $this->getGenderOptions(),
                    'constraints' => [
                        new Required(),
                    ],
                ]
            );

        if (self::UPDATE === $this->type)
        {
            $this->addChoice(
                'default_billing_address',
                [
                    'label' => 'Billing Address',
                    'placeholder' => 'Select one',
                    'choices'     => $this->getAddressOptions(),
                ]
            )
            ->addChoice(
                'default_shipping_address',
                [
                    'label' => 'Shipping Address',
                    'placeholder' => 'Select one',
                    'choices'     => $this->getAddressOptions(),
                ]
            );
        }

        $this->addTextarea(
            'comment',
            [
                'label'       => 'Comment',
                'constraints' => [
                    new Length(['max' => 255]),
                ],
            ]
        )
        ->addSubmit(
            'submit',
            [
                'label' => (self::UPDATE === $this->type ? 'Update' : 'Add'),
            ]
        );

        return $this;
    }

    /**
     * @return array
     */
    protected function populateFormFields()
    {
        $result = [];

        $idCustomer = $this->request->get('id_customer');
        if (false === is_null($idCustomer))
        {
            $customerDetailEntity = $this
                ->customerQuery
                ->findOneByIdCustomer($idCustomer);

            $result = $customerDetailEntity->toArray();
        }

        return $result;
    }

    /**
     * @return array
     */
    protected function getGenderOptions()
    {
        return [
            0 => SpyCustomerTableMap::COL_GENDER_MALE,
            1 => SpyCustomerTableMap::COL_GENDER_FEMALE,
        ];
    }

    /**
     * @return array
     */
    protected function getSalutationOptions()
    {
        return [
            0 => SpyCustomerTableMap::COL_SALUTATION_MR,
            1 => SpyCustomerTableMap::COL_SALUTATION_MRS,
            2 => SpyCustomerTableMap::COL_SALUTATION_DR,
        ];
    }

    protected function getAddressOptions()
    {
        return [];
    }


    /**
     * @return CustomerFacade
     */
    protected function getCustomerFacade()
    {
        return $this->getLocator()
            ->customer()
            ->facade();
    }

}
