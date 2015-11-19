<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication\Form;

use SprykerEngine\Zed\Gui\Communication\Form\AbstractForm;
use SprykerFeature\Zed\Customer\CustomerConfig;
use SprykerFeature\Zed\Customer\Persistence\CustomerQueryContainerInterface;
use Symfony\Component\Form\FormTypeInterface;

class AddressForm extends AbstractForm
{

    /**
     * @var CustomerQueryContainerInterface
     */
    protected $customerQueryContainer;

    /**
     * @param FormTypeInterface $formTypeInterface
     * @param CustomerQueryContainerInterface $customerQueryContainer
     */
    public function __construct(FormTypeInterface $formTypeInterface, CustomerQueryContainerInterface $customerQueryContainer)
    {
        parent::__construct($formTypeInterface);

        $this->customerQueryContainer = $customerQueryContainer;
    }

    /**
     * @return array
     */
    public function populateFormFields()
    {
        $idCustomerAddress = $this->getRequest()->query->getInt(CustomerConfig::PARAM_ID_CUSTOMER_ADDRESS);

        if ($idCustomerAddress === 0) {
            return [];
        }

        $address = $this->customerQueryContainer->queryAddress($idCustomerAddress)->findOne();

        return $address->toArray();
    }

}
