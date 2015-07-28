<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Zed\Ide\FactoryAutoCompletion\CustomerCommunication;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Customer\Business\CustomerFacade;
use SprykerFeature\Zed\Customer\Communication\CustomerDependencyContainer;
use SprykerFeature\Zed\Customer\Persistence\CustomerQueryContainerInterface;

/**
 * @method CustomerCommunication getFactory()
 * @method CustomerQueryContainerInterface getQueryContainer()
 * @method CustomerDependencyContainer getDependencyContainer()
 * @method CustomerFacade getFacade()
 */
class AddController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        $form = $this->getDependencyContainer()
            ->createCustomerForm('add')
        ;

        $form->handleRequest();

        if (true === $form->isValid()) {
            $data = $form->getData();

            $customerTransfer = $this->createCustomerTransfer();
            $customerTransfer->fromArray($data, true);

            $this->getFacade()
                ->registerCustomer($customerTransfer)
            ;

            return $this->redirectResponse('/customer/');
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return CustomerTransfer
     */
    protected function createCustomerTransfer()
    {
        return new CustomerTransfer();
    }

}
