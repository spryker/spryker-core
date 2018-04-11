<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Controller;

use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
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
    public const PARAM_TYPE = 'type';
    public const PARAM_REDIRECT_URL = 'redirect-url';

    protected const ERROR_MESSAGE_INVALID_DATA_PROVIDED = 'Invalid data provided.';
    protected const SUCCESSFUL_MESSAGE_CUSTOMER_CREATED = 'Customer is registered successfully.';
    protected const SUCCESSFUL_MESSAGE_ORDER_CREATED = 'Order is created successfully.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $quoteTransfer = $this->getInitialQuote($request);

        $forms = [];
        $validForms = true;
        $allFormPlugins = $this->getFactory()->getManualOrderEntryFormPlugins();
        $filteredFormPlugins = $this->getFactory()->getManualOrderEntryFilteredFormPlugins($request, $quoteTransfer);

        foreach ($filteredFormPlugins as $formPlugin) {
            $form = $formPlugin->createForm($request, $quoteTransfer);
            $form->setData($quoteTransfer->toArray());
            $form->handleRequest($request);

            $data = $form->getData();

            $modifiedData = $data->modifiedToArray();
            $quoteTransfer->fromArray($modifiedData);

            if ($form->isValid()) {
                $quoteTransfer = $formPlugin->handleData($quoteTransfer, $form, $request);
            } else {
                $validForms = false;
            }

            $forms[] = $form;
        }

        if ($validForms
            && count($allFormPlugins)
            && count($allFormPlugins) == count($filteredFormPlugins)
        ) {
            $checkoutResponseTransfer = $this->createOrder($quoteTransfer);

            if ($checkoutResponseTransfer->getIsSuccess()) {
                $redirectUrl = $this->createRedirectUrlAfterOrderCreation($checkoutResponseTransfer->getSaveOrder(), $request);

                return $this->redirectResponse($redirectUrl);
            }
        }

        $formsView = [];
        foreach ($forms as $form) {
            $formsView[] = $form->createView();
        }

        return $this->viewResponse([
            'forms' => $formsView,
            'nextStepName' => $this->getFactory()->getConfig()->getNextStepName(),
            'quoteTransfer' => $quoteTransfer,
            'params' => $request->query->all(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
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
                    $this->addSuccessMessage(static::SUCCESSFUL_MESSAGE_CUSTOMER_CREATED);
                    $redirectUrl = $this->createRedirectUrlAfterUserCreation(
                        $customerResponseTransfer->getCustomerTransfer(),
                        $request
                    );

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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function createOrder(QuoteTransfer $quoteTransfer)
    {
        $checkoutResponseTransfer = $this->getFactory()
            ->getCheckoutFacade()
            ->placeOrder($quoteTransfer);

        if ($checkoutResponseTransfer->getIsSuccess()) {
            $this->addSuccessMessage(static::SUCCESSFUL_MESSAGE_ORDER_CREATED);
        } else {
            $this->addErrorMessage(static::ERROR_MESSAGE_INVALID_DATA_PROVIDED);
        }

        return $checkoutResponseTransfer;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $customerForm
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
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
        /** @var \Generated\Shared\Transfer\CustomerTransfer $customerTransfer */
        $customerTransfer = $customerForm->getData();
        $customerTransfer->setPassword(uniqid());
        $customerTransfer->setSendPasswordToken(true);

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    protected function createRedirectUrlAfterUserCreation(CustomerTransfer $customerTransfer, Request $request)
    {
        $params = $request->query->all();
        $params[CustomersListType::FIELD_CUSTOMER] = $customerTransfer->getIdCustomer();

        return Url::generate(
            '/manual-order-entry-gui/create',
            $params
        )
            ->build();
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed|string
     */
    protected function createRedirectUrlAfterOrderCreation(SaveOrderTransfer $saveOrderTransfer, Request $request)
    {
        $redirectUrl = $request->get(static::PARAM_REDIRECT_URL);

        if ($redirectUrl) {
            return $redirectUrl;
        }

        return Url::generate(
            '/sales/detail',
            [SalesConfig::PARAM_ID_SALES_ORDER => $saveOrderTransfer->getIdSalesOrder()]
        )->build();
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

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getInitialQuote(Request $request): QuoteTransfer
    {
        $quoteTransfer = new QuoteTransfer();

        foreach ($this->getFactory()->getQuoteExpanderPlugins() as $quoteExpanderPlugin) {
            $quoteTransfer = $quoteExpanderPlugin->expand($quoteTransfer, $request);
        }

        return $quoteTransfer;
    }
}
