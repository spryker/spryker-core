<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Controller;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
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

    const SUCCESSFUL_MESSAGE_CUSTOMER_CREATED = 'Customer is registered successfully.';
    const SUCCESSFUL_MESSAGE_ORDER_CREATED = 'Order is created successfully.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function indexAction(Request $request)
    {
        $quoteTransfer = new QuoteTransfer();
        // @todo @Artem Add select Currency field
        $currencyTransfer = new CurrencyTransfer();
        $currencyTransfer->setCode('EUR');
        $currencyTransfer->setIdCurrency(93);
        $quoteTransfer->setCurrency($currencyTransfer);

        $storeTransfer = new StoreTransfer();
        $storeTransfer->setIdStore(1);
        $storeTransfer->setName('DE');
        $quoteTransfer->setStore($storeTransfer);

        $forms = [];
        $validForms = true;

        foreach ($this->getFactory()->getManualOrderEntryFormPlugins($request, $quoteTransfer) as $formPlugin) {
            $form = $formPlugin->createForm($request, $quoteTransfer);
            $form->setData($quoteTransfer->toArray());
            $form->handleRequest($request);

            $data = $form->getData();

            $modifiedData = $data->modifiedToArray();
            $quoteTransfer->fromArray($modifiedData);

            if ($form->isValid()) {
                $quoteTransfer = $formPlugin->handleData($quoteTransfer);
            } else {
                $validForms = false;
            }

            $forms[] = $form;
        }

        if ($validForms) {
            $this->createOrder($quoteTransfer);

            // @todo @Artem redirect to order details
            if (0 && !empty($quoteTransfer)) {
                $redirectUrl = $this->createRedirectUrlAfterOrderCreation($quoteTransfer);

                return $this->redirectResponse($redirectUrl);
            }
        }

        $formsView = [];
        foreach ($forms as $form) {
            $formsView[] = $form->createView();
        }

        return $this->viewResponse([
            'forms' => $formsView,
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createOrder(QuoteTransfer $quoteTransfer)
    {
//        $quoteTransfer = $manualOrderEntryForm->getData();

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
