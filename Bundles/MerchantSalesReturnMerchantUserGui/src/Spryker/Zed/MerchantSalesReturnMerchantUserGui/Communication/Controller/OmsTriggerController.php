<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantOmsTriggerRequestTransfer;
use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\MerchantSalesReturnMerchantUserGuiCommunicationFactory getFactory()
 */
class OmsTriggerController extends AbstractController
{
    protected const URL_PARAM_MERCHANT_SALES_ORDER_ITEM_REFERENCE = 'merchant-sales-order-item-reference';
    protected const URL_PARAM_MERCHANT_SALES_ORDER_REFERENCE = 'merchant-sales-order-reference';
    protected const URL_PARAM_REDIRECT = 'redirect';
    protected const URL_PARAM_EVENT = 'event';

    protected const MESSAGE_STATUS_CHANGED_SUCCESS = 'Status change triggered successfully.';

    protected const ERROR_INVALID_REQUEST = 'Request is invalid';

    /**
     * @uses \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Controller\DetailController::ROUTE_REDIRECT
     */
    protected const REDIRECT_URL_DEFAULT = '/merchant-sales-return-merchant-user-gui/detail';

    protected const MESSAGE_ORDER_NOT_FOUND_ERROR = 'Merchant sales order #%d not found.';
    protected const MESSAGE_REDIRECT_NOT_FOUND_ERROR = 'Parameter redirect not found.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function submitTriggerEventAction(Request $request): RedirectResponse
    {
        $redirect = $request->query->get('redirect', static::URL_PARAM_REDIRECT);

        if (!$redirect) {
            $this->addErrorMessage(static::MESSAGE_REDIRECT_NOT_FOUND_ERROR);
            $redirectUrl = Url::generate(static::REDIRECT_URL_DEFAULT)->build();

            return $this->redirectResponse($redirectUrl);
        }

        $form = $this->getFactory()
            ->createEventTriggerForm()
            ->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->addErrorMessage(static::ERROR_INVALID_REQUEST);

            return $this->redirectResponse($redirect);
        }

        $event = $request->query->get(static::URL_PARAM_EVENT);
        $merchantOrderReference = $request->get(static::URL_PARAM_MERCHANT_SALES_ORDER_REFERENCE);

        $merchantOrderTransfer = $this->findMerchantOrder($merchantOrderReference);

        if (!$merchantOrderTransfer) {
            $this->addErrorMessage(static::MESSAGE_ORDER_NOT_FOUND_ERROR, ['%d' => $merchantOrderReference]);
            $redirectUrl = Url::generate(static::REDIRECT_URL_DEFAULT)->build();

            return $this->redirectResponse($redirectUrl);
        }

        $countTriggeredItems = $this->getFactory()
            ->getMerchantOmsFacade()
            ->triggerEventForMerchantOrderItems(
                (new MerchantOmsTriggerRequestTransfer())
                    ->setMerchantOmsEventName($event)
                    ->setMerchantOrderItems($merchantOrderTransfer->getMerchantOrderItems())
            );

        if (!$countTriggeredItems) {
            $this->addErrorMessage(static::ERROR_INVALID_REQUEST);

            return $this->redirectResponse($redirect);
        }

        $this->addInfoMessage(static::MESSAGE_STATUS_CHANGED_SUCCESS);

        return $this->redirectResponse($redirect);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function submitTriggerEventItemAction(Request $request): RedirectResponse
    {
        $redirect = $request->query->get('redirect', static::URL_PARAM_REDIRECT);

        if (!$redirect) {
            $this->addErrorMessage(static::MESSAGE_REDIRECT_NOT_FOUND_ERROR);
            $redirectUrl = Url::generate(static::REDIRECT_URL_DEFAULT)->build();

            return $this->redirectResponse($redirectUrl);
        }

        $form = $this->getFactory()
            ->createEventItemTriggerForm()
            ->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->addErrorMessage(static::ERROR_INVALID_REQUEST);

            return $this->redirectResponse($redirect);
        }

        $event = $request->query->get(static::URL_PARAM_EVENT);
        $merchantSalesOrderItemReference = $request->query->get(static::URL_PARAM_MERCHANT_SALES_ORDER_ITEM_REFERENCE);

        $merchantOmsTriggerResponseTransfer = $this->getFactory()
            ->getMerchantOmsFacade()
            ->triggerEventForMerchantOrderItem(
                (new MerchantOmsTriggerRequestTransfer())
                    ->setMerchantOmsEventName($event)
                    ->setMerchantOrderItemReference($merchantSalesOrderItemReference)
            );

        if (!$merchantOmsTriggerResponseTransfer->getIsSuccessful()) {
            /** @var string $message */
            $message = $merchantOmsTriggerResponseTransfer->requireMessage()->getMessage();

            $this->addErrorMessage($message);

            return $this->redirectResponse($redirect);
        }

        $this->addInfoMessage(static::MESSAGE_STATUS_CHANGED_SUCCESS);

        return $this->redirectResponse($redirect);
    }

    /**
     * @param string $merchantOrderReference
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer|null
     */
    protected function findMerchantOrder(string $merchantOrderReference): ?MerchantOrderTransfer
    {
        return $this->getFactory()->getMerchantSalesOrderFacade()->findMerchantOrder(
            (new MerchantOrderCriteriaTransfer())
                ->setMerchantOrderReference($merchantOrderReference)
                ->setWithItems(true)
        );
    }
}
