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
 * @method \Spryker\Zed\Customer\Business\CustomerFacadeInterface getFacade()
 * @method \Spryker\Zed\Customer\Communication\CustomerCommunicationFactory getFactory()
 */
class AddController extends AbstractController
{
    const MESSAGE_CUSTOMER_CREATE_SUCCESS = 'Customer was created successfully.';
    const MESSAGE_CUSTOMER_CREATE_ERROR = 'Customer was not created.';

    const REDIRECT_URL_DEFAULT = '/customer';
    const REDIRECT_URL_KEY = 'redirectUrl';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $baseRedirectUrl = $request->query->get(static::REDIRECT_URL_KEY, static::REDIRECT_URL_DEFAULT);
        $dataProvider = $this->getFactory()->createCustomerFormDataProvider();

        $form = $this->getFactory()
            ->createCustomerForm(
                $dataProvider->getData(),
                array_merge(
                    $dataProvider->getOptions(),
                    ['action' => "/customer/add?redirectUrl=" . urlencode($baseRedirectUrl)]
                )
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customerTransfer = new CustomerTransfer();
            $customerTransfer->fromArray($form->getData(), true);

            $customerResponseTransfer = $this->getFacade()->registerCustomer($customerTransfer);

            if (!$customerResponseTransfer->getIsSuccess()) {
                $this->addErrorMessage(static::MESSAGE_CUSTOMER_CREATE_ERROR);
                return $this->redirectResponse($baseRedirectUrl);
            }

            $this->addSuccessMessage(static::MESSAGE_CUSTOMER_CREATE_SUCCESS);
            return $this->redirectResponse($this->getSuccessRedirectUrl($baseRedirectUrl, $customerTransfer));
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param string $baseRedirectUrl
     * @param \Generated\Shared\Transfer\CustomerTransfer $customer
     *
     * @return string
     */
    protected function getSuccessRedirectUrl(string $baseRedirectUrl, CustomerTransfer $customer): string
    {
        return $baseRedirectUrl . (parse_url($baseRedirectUrl, PHP_URL_QUERY) ? '&' : '?')
            . "customerReference={$customer->getCustomerReference()}";
    }
}
