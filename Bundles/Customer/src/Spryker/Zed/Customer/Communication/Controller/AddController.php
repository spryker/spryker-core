<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Communication\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Customer\Business\CustomerFacade getFacade()
 * @method \Spryker\Zed\Customer\Communication\CustomerCommunicationFactory getFactory()
 */
class AddController extends AbstractController
{
    const MESSAGE_CUSTOMER_CREATE_SUCCESS = 'Customer was created successfully.';
    const MESSAGE_CUSTOMER_CREATE_ERROR = 'Customer was not created.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $dataProvider = $this->getFactory()->createCustomerFormDataProvider();

        $form = $this->getFactory()
            ->createCustomerForm(
                $dataProvider->getData(),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($form->isValid()) {
            $customerTransfer = new CustomerTransfer();
            $customerTransfer->fromArray($form->getData(), true);

            $customerResponseTransfer = $this->getFacade()->registerCustomer($customerTransfer);

            if (!$customerResponseTransfer->getIsSuccess()) {
                $this->addErrorMessage(static::MESSAGE_CUSTOMER_CREATE_ERROR);
                return $this->redirectResponse('/customer');
            }

            $this->addSuccessMessage(static::MESSAGE_CUSTOMER_CREATE_SUCCESS);
            return $this->redirectResponse('/customer');
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function createCustomerTransfer()
    {
        return new CustomerTransfer();
    }
}
