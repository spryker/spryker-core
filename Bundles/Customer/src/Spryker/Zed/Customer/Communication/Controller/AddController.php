<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Customer\Communication\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Customer\Business\CustomerFacade;
use Spryker\Zed\Customer\Communication\CustomerCommunicationFactory;
use Spryker\Zed\Customer\Communication\Form\CustomerForm;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method CustomerFacade getFacade()
 * @method CustomerCommunicationFactory getFactory()
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
        $form = $this->getFactory()
            ->createCustomerForm(CustomerForm::ADD);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $customerTransfer = $form->getData();

            $this->getFacade()
                ->registerCustomer($customerTransfer);

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
