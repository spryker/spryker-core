<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Customer\Business\CustomerFacade;
use SprykerFeature\Zed\Customer\Communication\CustomerDependencyContainer;
use SprykerFeature\Zed\Customer\Communication\Form\CustomerForm;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method CustomerFacade getFacade()
 * @method CustomerDependencyContainer getDependencyContainer()
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
            ->createCustomerForm(CustomerForm::ADD)
        ;

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
