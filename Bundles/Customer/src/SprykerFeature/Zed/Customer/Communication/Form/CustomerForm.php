<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication\Form;

use Generated\Shared\Transfer\CustomerTransfer;

use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomerQuery;

use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;

use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

class CustomerForm extends AbstractForm
{
    /**
     * @var SpyCustomerQueryQuery
     */
    protected $customerQuery;

    /**
     * @param SpyCustomerQuery $customerQuery
     */
    public function __construct(SpyCustomerQuery $customerQuery)
    {
        $this->customerQuery = $customerQuery;
    }

    /**
     * @return $this
     */
    public function buildFormFields()
    {

        return $this->addHidden(
            'IdCustomer',
            [
                'label' => 'Customer ID',
            ]
        )
            ->addChoice(
                'salutation',
                [
                    'label'   => 'Salutation',
                    'placeholder' => 'Select one',
                    'choices' => $this->getSalutationOptions(),
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
            ->addText(
                'email',
                [
                    'label'       => 'Email',
                    'constraints' => [
                        new NotBlank(),
                        new Required(),
                        new Email()
                    ],
                ]
            )
            ->addChoice(
                'gender',
                [
                    'label'   => 'Gender',
                    'placeholder' => 'Select one',
                    'choices' => $this->getGenderOptions(),
                ]
            )
            ->addTextarea(
                'comment',
                [
                    'label' => 'Comment',
                    'constraints' => [
                        new Length(['max' => 255])
                    ]
                ]
            )->addSubmit();
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
            0 => 'Unknown',
            1 => 'Male',
            2 => 'Female',
        ];
    }

    /**
     * @return array
     */
    protected function getSalutationOptions()
    {
        return [
            0 => 'Mr.',
            1 => 'Mrs.',
            2 => 'Dr.',
        ];
    }

}
