<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Communication\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Customer\Business\CustomerFacadeInterface getFacade()
 * @method \Spryker\Zed\Customer\Communication\CustomerCommunicationFactory getFactory()
 * @method \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Customer\Persistence\CustomerRepositoryInterface getRepository()
 */
class AddController extends AbstractController
{
    public const MESSAGE_CUSTOMER_CREATE_SUCCESS = 'Customer was created successfully.';
    public const MESSAGE_CUSTOMER_CREATE_ERROR = 'Customer was not created.';

    public const REDIRECT_URL_DEFAULT = '/customer';
    public const REDIRECT_URL_KEY = 'redirectUrl';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $baseRedirectUrl = urldecode($request->query->get(static::REDIRECT_URL_KEY, static::REDIRECT_URL_DEFAULT));
        $dataProvider = $this->getFactory()->createCustomerFormDataProvider();

        $form = $this->getFactory()
            ->createCustomerForm(
                $dataProvider->getData(),
                array_merge(
                    $dataProvider->getOptions(),
                    [
                        'action' => Url::generate('/customer/add', [static::REDIRECT_URL_KEY => $baseRedirectUrl]),
                    ]
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
        $redirectUrl = Url::parse($baseRedirectUrl);
        $redirectUrl->addQuery('customerReference', $customer->getCustomerReference());

        return $redirectUrl->build();
    }
}
