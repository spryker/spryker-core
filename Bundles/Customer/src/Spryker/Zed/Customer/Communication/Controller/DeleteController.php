<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Communication\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Shared\Customer\CustomerConstants;
use Spryker\Zed\Customer\Business\Exception\CustomerNotFoundException;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Customer\Business\CustomerFacadeInterface getFacade()
 * @method \Spryker\Zed\Customer\Communication\CustomerCommunicationFactory getFactory()
 * @method \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Customer\Persistence\CustomerRepositoryInterface getRepository()
 */
class DeleteController extends AbstractController
{
    protected const URL_CUSTOMER_LIST_PAGE = '/customer';
    protected const URL_CUSTOMER_DELETE_PAGE = '/customer/delete';

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

            return $this->redirectResponse(static::URL_CUSTOMER_LIST_PAGE);
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
    public function confirmAction(Request $request): RedirectResponse
    {
        $customerDeleteForm = $this->getFactory()->getCustomerDeleteForm();
        $customerDeleteForm->handleRequest($request);

        if (!$customerDeleteForm->isSubmitted()) {
            $this->addErrorMessage('Form was not submitted.');

            return $this->createRedirectResponseToCustomerDeletePage($customerDeleteForm);
        }

        if (!$customerDeleteForm->isValid()) {
            foreach ($customerDeleteForm->getErrors(true) as $formError) {
                /** @var \Symfony\Component\Form\FormError $formError */
                $this->addErrorMessage($formError->getMessage(), $formError->getMessageParameters());
            }

            return $this->createRedirectResponseToCustomerDeletePage($customerDeleteForm);
        }

        try {
            $this->getFacade()->anonymizeCustomer($customerDeleteForm->getData());
        } catch (CustomerNotFoundException $exception) {
            $this->addErrorMessage('Customer does not exist');

            return $this->redirectResponse(static::URL_CUSTOMER_LIST_PAGE);
        }

        $this->addSuccessMessage('Customer successfully deleted');

        return $this->redirectResponse(static::URL_CUSTOMER_LIST_PAGE);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $customerDeleteForm
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function createRedirectResponseToCustomerDeletePage(FormInterface $customerDeleteForm): RedirectResponse
    {
        /** @var \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer */
        $customerTransfer = $customerDeleteForm->getData();

        if ($customerTransfer && $customerTransfer->getIdCustomer()) {
            return $this->redirectResponse(
                Url::generate(static::URL_CUSTOMER_DELETE_PAGE, [
                    CustomerConstants::PARAM_ID_CUSTOMER => $customerTransfer->getIdCustomer(),
                ])
            );
        }

        return $this->redirectResponse(static::URL_CUSTOMER_LIST_PAGE);
    }
}
