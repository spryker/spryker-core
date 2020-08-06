<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantOmsTriggerRequestTransfer;
use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantSalesOrderGui\Communication\MerchantSalesOrderGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantSalesOrderGui\Business\MerchantSalesOrderGuiFacadeInterface getFacade()
 */
class OmsTriggerController extends AbstractController
{
    protected const URL_PARAM_MERCHANT_SALES_ORDER_ITEM_REFERENCE = 'merchant-sales-order-item-reference';
    protected const URL_PARAM_ID_MERCHANT_SALES_ORDER = 'id-merchant-sales-order';
    protected const URL_PARAM_REDIRECT = 'redirect';
    protected const URL_PARAM_EVENT = 'event';

    protected const MESSAGE_STATUS_CHANGED_SUCCESSFULLY = 'Status change triggered successfully.';

    protected const ERROR_INVALID_REQUEST = 'Request is invalid';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function submitTriggerEventForMerchantOrderAction(Request $request): RedirectResponse
    {
        $redirect = $request->query->get('redirect', static::URL_PARAM_REDIRECT);

        $form = $this->getFactory()
            ->createEventTriggerForm()
            ->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->addErrorMessage(static::ERROR_INVALID_REQUEST);

            return $this->redirectResponse($redirect);
        }

        $event = $request->query->get(static::URL_PARAM_EVENT);
        $idMerchantOrder = $request->query->getInt(static::URL_PARAM_ID_MERCHANT_SALES_ORDER);

        $merchantOrderTransfer = $this->getFactory()->getMerchantSalesOrderFacade()->findMerchantOrder(
            (new MerchantOrderCriteriaTransfer())
                ->setIdMerchantOrder($idMerchantOrder)
                ->setWithItems(true)
        );

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

        $this->addInfoMessage(static::MESSAGE_STATUS_CHANGED_SUCCESSFULLY);

        return $this->redirectResponse($redirect);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function submitTriggerEventForMerchantOrderItemAction(Request $request): RedirectResponse
    {
        $redirect = $request->query->get('redirect', static::URL_PARAM_REDIRECT);

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
            $this->addErrorMessage($merchantOmsTriggerResponseTransfer->getMessage());

            return $this->redirectResponse($redirect);
        }

        $this->addInfoMessage(static::MESSAGE_STATUS_CHANGED_SUCCESSFULLY);

        return $this->redirectResponse($redirect);
    }
}
