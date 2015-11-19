<?php

namespace SprykerFeature\Zed\Customer\Communication\Form;

use SprykerEngine\Zed\Gui\Communication\Form\AbstractForm;
use SprykerFeature\Zed\Customer\CustomerConfig;
use SprykerFeature\Zed\Customer\Persistence\CustomerQueryContainerInterface;
use Symfony\Component\Form\FormTypeInterface;

class CustomerForm extends AbstractForm
{

    /**
     * @var CustomerQueryContainerInterface
     */
    protected $customerQueryContainer;

    /**
     * @var string
     */
    protected $formActionType;

    /**
     * @param CustomerQueryContainerInterface $customerQueryContainer
     * @param string $formActionType
     */
    public function __construct(FormTypeInterface $formTypeInterface, CustomerQueryContainerInterface $customerQueryContainer, $formActionType)
    {
        parent::__construct($formTypeInterface);

        $this->customerQueryContainer = $customerQueryContainer;
        $this->formActionType = $formActionType;
    }

    /**
     * @return array
     */
    protected function populateFormFields()
    {
        $idCustomer = $this->getRequest()->query->get(CustomerConfig::PARAM_ID_CUSTOMER, null);

        if (empty($idCustomer)) {
            return [];
        }

        $customerEntity = $this
            ->customerQueryContainer
            ->queryCustomerById($idCustomer)
            ->findOne()
        ;

        return $customerEntity->toArray();
    }

}
