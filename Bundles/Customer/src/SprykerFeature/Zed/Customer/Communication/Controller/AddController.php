<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Zed\Ide\FactoryAutoCompletion\CustomerCommunication;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Customer\Business\CustomerFacade;
use SprykerFeature\Zed\Customer\Communication\Form\CustomerTypeForm;
use SprykerFeature\Zed\Customer\Persistence\CustomerQueryContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method CustomerCommunication getFactory()
 * @method CustomerQueryContainerInterface getQueryContainer()
 * @method CustomerFacade getFacade()
 */
class AddController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return array|RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $form = $this->getDependencyContainer()
            ->createCustomerForm(new CustomerTransfer(), CustomerTypeForm::ADD)
        ;
        $form->handleRequest($request);

        if ($form->isValid() === true) {
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
