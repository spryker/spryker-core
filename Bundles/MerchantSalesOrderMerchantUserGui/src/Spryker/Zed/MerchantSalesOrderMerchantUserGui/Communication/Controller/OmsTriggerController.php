<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantOmsTriggerRequestTransfer;
use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\MerchantSalesOrderMerchantUserGuiCommunicationFactory getFactory()
 */
class OmsTriggerController extends AbstractController
{
    /**
     * @var string
     */
    protected const URL_PARAM_MERCHANT_SALES_ORDER_ITEM_REFERENCE = 'merchant-sales-order-item-reference';

    /**
     * @var string
     */
    protected const URL_PARAM_ID_MERCHANT_SALES_ORDER = 'id-merchant-sales-order';

    /**
     * @var string
     */
    protected const URL_PARAM_REDIRECT = 'redirect';

    /**
     * @var string
     */
    protected const URL_PARAM_EVENT = 'event';

    /**
     * @var string
     */
    protected const MESSAGE_STATUS_CHANGED_SUCCESSFULLY = 'Status change triggered successfully.';

    /**
     * @var string
     */
    protected const ERROR_INVALID_REQUEST = 'Request is invalid';

    /**
     * @uses \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Controller\DetailController::ROUTE_REDIRECT
     *
     * @var string
     */
    protected const REDIRECT_URL_DEFAULT = '/merchant-sales-order-merchant-user-gui/detail';

    /**
     * @var string
     */
    protected const MESSAGE_ORDER_NOT_FOUND_ERROR = 'Merchant sales order #%d not found.';

    /**
     * @var string
     */
    protected const MESSAGE_REDIRECT_NOT_FOUND_ERROR = 'Parameter redirect not found.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function submitTriggerEventAction(Request $request): RedirectResponse
    {
        $redirect = (string)$request->query->get('redirect', static::URL_PARAM_REDIRECT);

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

        $event = (string)$request->query->get(static::URL_PARAM_EVENT);
        $idMerchantOrder = $request->query->getInt(static::URL_PARAM_ID_MERCHANT_SALES_ORDER);

        $merchantOrderTransfer = $this->findMerchantOrder($idMerchantOrder);

        if (!$merchantOrderTransfer) {
            $this->addErrorMessage(static::MESSAGE_ORDER_NOT_FOUND_ERROR, ['%d' => $idMerchantOrder]);
            $redirectUrl = Url::generate(static::REDIRECT_URL_DEFAULT)->build();

            return $this->redirectResponse($redirectUrl);
        }

        $countTriggeredItems = $this->getFactory()
            ->getMerchantOmsFacade()
            ->triggerEventForMerchantOrderItems(
                (new MerchantOmsTriggerRequestTransfer())
                    ->setMerchantOmsEventName($event)
                    ->setMerchantOrderItems($merchantOrderTransfer->getMerchantOrderItems()),
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
    public function submitTriggerEventItemAction(Request $request): RedirectResponse
    {
        $redirect = (string)$request->query->get('redirect', static::URL_PARAM_REDIRECT);

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

        $event = (string)$request->query->get(static::URL_PARAM_EVENT);
        $merchantSalesOrderItemReference = (string)$request->query->get(static::URL_PARAM_MERCHANT_SALES_ORDER_ITEM_REFERENCE);

        $merchantOmsTriggerResponseTransfer = $this->getFactory()
            ->getMerchantOmsFacade()
            ->triggerEventForMerchantOrderItem(
                (new MerchantOmsTriggerRequestTransfer())
                    ->setMerchantOmsEventName($event)
                    ->setMerchantOrderItemReference($merchantSalesOrderItemReference),
            );

        if (!$merchantOmsTriggerResponseTransfer->getIsSuccessful()) {
            /** @var string $message */
            $message = $merchantOmsTriggerResponseTransfer->requireMessage()->getMessage();

            $this->addErrorMessage($message);

            return $this->redirectResponse($redirect);
        }

        $this->addInfoMessage(static::MESSAGE_STATUS_CHANGED_SUCCESSFULLY);

        return $this->redirectResponse($redirect);
    }

    /**
     * @param int $idMerchantOrder
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer|null
     */
    protected function findMerchantOrder(int $idMerchantOrder): ?MerchantOrderTransfer
    {
        return $this->getFactory()->getMerchantSalesOrderFacade()->findMerchantOrder(
            (new MerchantOrderCriteriaTransfer())
                ->setIdMerchantOrder($idMerchantOrder)
                ->setWithItems(true),
        );
    }
}
