<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Controller;

use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Customer\CustomersListType;
use Spryker\Zed\Sales\SalesConfig;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ManualOrderEntryGui\Communication\ManualOrderEntryGuiCommunicationFactory getFactory()
 */
class CreateController extends AbstractController
{
    const ERROR_MESSAGE_INVALID_DATA_PROVIDED = 'Invalid data provided.';

    const SUCCESSFUL_MESSAGE_CUSTOMER_CREATED = 'Customer was registered successfully.';
    const SUCCESSFUL_MESSAGE_ORDER_CREATED = 'Order was created successfully.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function indexAction(Request $request)
    {
        $checkoutFormDataProvider = $this->getFactory()
            ->createCheckoutFormDataProvider($request);

        $checkoutForm = $this->getFactory()
            ->createCheckoutForm($checkoutFormDataProvider);
        $checkoutForm->handleRequest($request);

        if ($checkoutForm->isSubmitted()) {
            if ($checkoutForm->isValid()) {
                // @todo @Artem Manage subform activation.
                // Some simple Step Engine inject to form

                $quoteTransfer = $this->createOrder($checkoutForm);

                // @todo @Artem redirect to order details
                if (0 && !empty($quoteTransfer)) {
                    $redirectUrl = $this->createRedirectUrlAfterOrderCreation($quoteTransfer);

                    return $this->redirectResponse($redirectUrl);
                }
            } else {
                $this->addErrorMessage(static::ERROR_MESSAGE_INVALID_DATA_PROVIDED);
            }
        }

        return $this->viewResponse([
            'checkoutForm' => $checkoutForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function customerAction(Request $request)
    {
        $customerFormDataProvider = $this->getFactory()
            ->createCustomerDataProvider();

        $customerForm = $this->getFactory()
            ->createCustomerForm($customerFormDataProvider)
            ->handleRequest($request);

        if ($customerForm->isSubmitted()) {
            if ($customerForm->isValid()) {
                $customerResponseTransfer = $this->registerCustomer($customerForm);

                if ($customerResponseTransfer->getIsSuccess()) {
                    $this->addSuccessMessage(self::SUCCESSFUL_MESSAGE_CUSTOMER_CREATED);
                    $redirectUrl = $this->createRedirectUrlAfterUserCreation($customerResponseTransfer->getCustomerTransfer());

                    return $this->redirectResponse($redirectUrl);
                }

                $this->processResponseErrors($customerResponseTransfer);

            } else {
                $this->addErrorMessage(static::ERROR_MESSAGE_INVALID_DATA_PROVIDED);
            }
        }

        return $this->viewResponse([
            'customerForm' => $customerForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $checkoutForm
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    protected function createOrder(FormInterface $checkoutForm)
    {
        $quoteTransfer = $checkoutForm->getData();

        try {
            // @todo @Artem create Order here
            $quoteTransfer->setIdOrder(1);

            $this->addSuccessMessage(static::SUCCESSFUL_MESSAGE_ORDER_CREATED);
        } catch (\Exception $exception) {
            $this->addErrorMessage(static::ERROR_MESSAGE_INVALID_DATA_PROVIDED);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $customerForm
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    protected function registerCustomer(FormInterface $customerForm)
    {
        $customerTransfer = $this->getCustomerTransferFromForm($customerForm);

        $customerFacade = $this->getFactory()->getCustomerFacade();
        $customerResponseTransfer = $customerFacade
            ->registerCustomer($customerTransfer);

        return $customerResponseTransfer;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $customerForm
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function getCustomerTransferFromForm(FormInterface $customerForm)
    {
        /** @var CustomerTransfer $customerTransfer */
        $customerTransfer = $customerForm->getData();
        $customerTransfer->setPassword(uniqid());
        $customerTransfer->setSendPasswordToken(true);

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return string
     */
    protected function createRedirectUrlAfterUserCreation(CustomerTransfer $customerTransfer)
    {
        return Url::generate(
            '/manual-order-entry-gui/create',
            [CustomersListType::FIELD_CUSTOMER => $customerTransfer->getIdCustomer()]
        )
            ->build();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function createRedirectUrlAfterOrderCreation(QuoteTransfer $quoteTransfer)
    {
        return Url::generate(
            '/sales/detail',
            [SalesConfig::PARAM_ID_SALES_ORDER => $quoteTransfer->getIdOrder()]
        )
            ->build();
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     *
     * @return void
     */
    protected function processResponseErrors(CustomerResponseTransfer $customerResponseTransfer)
    {
        foreach ($customerResponseTransfer->getErrors() as $errorTransfer) {
            $this->addErrorMessage($errorTransfer->getMessage());
        }
    }

}
