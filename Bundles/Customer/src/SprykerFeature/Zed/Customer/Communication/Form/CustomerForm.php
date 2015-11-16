<?php

namespace SprykerFeature\Zed\Customer\Communication\Form;

use SprykerEngine\Zed\Gui\Communication\Form\AbstractForm;
use SprykerFeature\Zed\Customer\Persistence\CustomerQueryContainerInterface;


class CustomerForm extends AbstractForm
{

    /**
     * @var CustomerQueryContainerInterface
     */
    protected $customerQueryContainer;

    /**
     * @param CustomerQueryContainerInterface $customerQuery
     * @param string $formType
     */
    public function __construct(CustomerQueryContainerInterface $customerQueryContainer, $formType)
    {
        $this->customerQueryContainer = $customerQueryContainer;
        $this->formType = $formType;
    }

    /**
     * @return array
     */
    protected function populateFormFields()
    {
        $idCustomer = $this->getRequest()->query->get('id-customer', null);

        if (empty($idCustomer)) {
            return [];
        }

        $customerEntity = $this
            ->customerQueryContainer
            ->queryCustomerById($idCustomer)
            ->findOne();

        return $customerEntity->toArray();
    }
}
