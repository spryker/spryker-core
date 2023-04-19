<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Communication\Controller;

use Generated\Shared\Transfer\OmsEventTriggerResponseTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\Oms\OmsConfig;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @method \Spryker\Zed\Oms\Business\OmsFacadeInterface getFacade()
 * @method \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Oms\Communication\OmsCommunicationFactory getFactory()
 * @method \Spryker\Zed\Oms\Persistence\OmsRepositoryInterface getRepository()
 */
class TriggerController extends AbstractController
{
    /**
     * @deprecated Exists for Backward Compatibility reasons only. Use static::REQUEST_PARAMETER_ITEMS instead.
     *
     * @var string
     */
    protected const REQUEST_PARAMETER_ID_SALES_ORDER_ITEM = 'id-sales-order-item';

    /**
     * @var string
     */
    protected const REQUEST_PARAMETER_ID_SALES_ORDER = 'id-sales-order';

    /**
     * @var string
     */
    protected const REQUEST_PARAMETER_ITEMS = 'items';

    /**
     * @var string
     */
    protected const REQUEST_PARAMETER_EVENT = 'event';

    /**
     * @var string
     */
    protected const REQUEST_PARAMETER_REDIRECT = 'redirect';

    /**
     * @var string
     */
    protected const MESSAGE_STATUS_CHANGED_SUCCESSFULLY = 'Status change triggered successfully.';

    /**
     * @var string
     */
    protected const ROUTE_REDIRECT_DEFAULT = '/';

    /**
     * @var string
     */
    protected const ERROR_INVALID_FORM = 'Form is invalid';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function submitTriggerEventForOrderItemsAction(Request $request)
    {
        /** @var string $redirect */
        $redirect = $request->query->get(static::REQUEST_PARAMETER_REDIRECT, static::ROUTE_REDIRECT_DEFAULT);
        if (!$this->isValidPostRequest($request)) {
            $this->addErrorMessage(static::ERROR_INVALID_FORM);

            return $this->redirectResponse($redirect);
        }

        $idOrderItems = $this->getRequestIdSalesOrderItems($request);
        if ($idOrderItems === []) {
            return $this->redirectResponse($redirect);
        }

        /** @var string $event */
        $event = $request->query->get(static::REQUEST_PARAMETER_EVENT);
        $triggerEventReturnData = $this->getFacade()->triggerEventForOrderItems($event, $idOrderItems);
        $this->addOmsEventTriggerStatusMessage($triggerEventReturnData);

        return $this->redirectResponse($redirect);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function submitTriggerEventForOrderAction(Request $request)
    {
        /** @var string $redirect */
        $redirect = $request->query->get('redirect', static::ROUTE_REDIRECT_DEFAULT);

        if (!$this->isValidPostRequest($request)) {
            $this->addErrorMessage(static::ERROR_INVALID_FORM);

            return $this->redirectResponse($redirect);
        }

        $idOrder = $this->castId($request->query->getInt(static::REQUEST_PARAMETER_ID_SALES_ORDER));

        /** @var string $event */
        $event = $request->query->get(static::REQUEST_PARAMETER_EVENT);

        /** @var string $redirect */
        $redirect = $request->query->get(static::REQUEST_PARAMETER_REDIRECT, '/');

        $orderItems = $this->getOrderItemsToTriggerAction($idOrder, $request->query->all(static::REQUEST_PARAMETER_ITEMS) ?: null);

        $triggerEventReturnData = $this->getFacade()->triggerEvent($event, $orderItems, []);
        $this->addOmsEventTriggerStatusMessage($triggerEventReturnData);

        return $this->redirectResponse($redirect);
    }

    /**
     * @param array<mixed>|null $triggerEventReturnData
     *
     * @return void
     */
    protected function addOmsEventTriggerStatusMessage(?array $triggerEventReturnData): void
    {
        $omsEventTriggerResponseTransfer = $triggerEventReturnData[OmsConfig::OMS_EVENT_TRIGGER_RESPONSE] ?? null;

        if (
            $omsEventTriggerResponseTransfer instanceof OmsEventTriggerResponseTransfer
            && $omsEventTriggerResponseTransfer->getIsSuccessful() === false
        ) {
            foreach ($omsEventTriggerResponseTransfer->getMessages() as $messageTransfer) {
                $this->addErrorMessage($messageTransfer->getValue());
            }

            return;
        }

        $this->addInfoMessage(static::MESSAGE_STATUS_CHANGED_SUCCESSFULLY);
    }

    /**
     * @param int $idOrder
     * @param array|null $itemsList
     *
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Sales\Persistence\SpySalesOrderItem>
     */
    protected function getOrderItemsToTriggerAction($idOrder, $itemsList = null)
    {
        $query = $this->getQueryContainer()->querySalesOrderItemsByIdOrder($idOrder);

        if (is_array($itemsList) && count($itemsList) > 0) {
            $query->filterByIdSalesOrderItem($itemsList, Criteria::IN);
        }

        $orderItems = $query->find();

        return $orderItems;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     *
     * @return bool
     */
    protected function isValidPostRequest(Request $request): bool
    {
        if (!$request->isMethod(Request::METHOD_POST)) {
            throw new BadRequestHttpException();
        }

        return $this->isTriggerFormValid($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function isTriggerFormValid(Request $request): bool
    {
        $form = $this->getFactory()
            ->createOmsTriggerFormFactory()
            ->createOmsTriggerForm()
            ->handleRequest($request);

        return $form->isSubmitted() && $form->isValid();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function getRequestIdSalesOrderItems(Request $request): array
    {
        $idOrderItems = $request->query->all(static::REQUEST_PARAMETER_ITEMS);

        // Exists for Backward Compatibility reasons only.
        $idOrderItem = $request->query->get(static::REQUEST_PARAMETER_ID_SALES_ORDER_ITEM);
        if ($idOrderItems === [] && $idOrderItem !== null) {
            $idOrderItems = [$idOrderItem];
        }

        return $idOrderItems;
    }
}
