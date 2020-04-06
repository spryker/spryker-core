<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Communication\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Shared\Customer\CustomerConstants;
use Spryker\Zed\Customer\Business\Exception\CustomerNotFoundException;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Customer\Business\CustomerFacadeInterface getFacade()
 * @method \Spryker\Zed\Customer\Communication\CustomerCommunicationFactory getFactory()
 * @method \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Customer\Persistence\CustomerRepositoryInterface getRepository()
 */
class DeleteController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $idCustomer = $this->castId($request->query->get(CustomerConstants::PARAM_ID_CUSTOMER));

        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setIdCustomer($idCustomer);

        try {
            $customerTransfer = $this->getFacade()->getCustomer($customerTransfer);
        } catch (CustomerNotFoundException $exception) {
            $this->addErrorMessage('Customer does not exist');

            return $this->redirectResponse('/customer');
        }

        $customerDeleteForm = $this->getFactory()->getCustomerDeleteForm();

        return $this->viewResponse([
            'customerDeleteForm' => $customerDeleteForm->setData($customerTransfer)
                ->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmAction(Request $request)
    {
        $customerDeleteForm = $this->getFactory()->getCustomerDeleteForm();
        $customerDeleteForm->handleRequest($request);

        if (!$customerDeleteForm->isSubmitted()) {
            $this->addErrorMessage('Form was not submitted.');

            return $this->redirectResponse('/customer');
        }

        if (!$customerDeleteForm->isValid()) {
            foreach ($customerDeleteForm->getErrors(true) as $formError) {
                /** @var \Symfony\Component\Form\FormError $formError */
                $this->addErrorMessage($formError->getMessage(), $formError->getMessageParameters());
            }

            return $this->redirectResponse('/customer');
        }

        try {
            $this->getFacade()->anonymizeCustomer($customerDeleteForm->getData());
        } catch (CustomerNotFoundException $exception) {
            $this->addErrorMessage('Customer does not exist');

            return $this->redirectResponse('/customer');
        }

        $this->addSuccessMessage('Customer successfully deleted');

        return $this->redirectResponse('/customer');
    }
}
