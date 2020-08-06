<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantSalesOrderGui\Communication\MerchantSalesOrderGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantSalesOrderGui\Business\MerchantSalesOrderGuiFacadeInterface getFacade()
 */
class OmsRenderFormController extends AbstractController
{
    protected const DEFAULT_REDIRECT_URL = 'redirectUrl';
    protected const URL_PARAM_EVENTS = 'events';
    protected const URL_PARAM_ID_MERCHANT_SALES_ORDER = 'idMerchantSalesOrder';
    protected const URL_PARAM_MERCHANT_SALES_ORDER_ITEM_REFERENCE = 'merchantSalesOrderItemReference';

    /**
     * @phpstan-return array<string, mixed>
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function orderAction(Request $request): array
    {
        $idMerchantSalesOrder = $this->castId($request->attributes->getInt(static::URL_PARAM_ID_MERCHANT_SALES_ORDER));
        $redirect = $request->attributes->get(static::DEFAULT_REDIRECT_URL);
        $events = $request->attributes->get(static::URL_PARAM_EVENTS);

        $eventTriggerFormDataProvider = $this->getFactory()->createEventTriggerFormDataProvider();

        $eventTriggerFormCollection = [];
        foreach ($events as $event) {
            $eventTriggerFormCollection[$event] = $this->getFactory()
                ->createEventTriggerForm($eventTriggerFormDataProvider->getOptions($idMerchantSalesOrder, $event, $redirect))
                ->createView();
        }

        return $this->viewResponse([
            'formCollection' => $eventTriggerFormCollection,
        ]);
    }

    /**
     * @phpstan-return array<string, mixed>
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function orderItemAction(Request $request): array
    {
        $merchantSalesOrderItemReference = $request->attributes->get(static::URL_PARAM_MERCHANT_SALES_ORDER_ITEM_REFERENCE);
        $redirect = $request->attributes->get(static::DEFAULT_REDIRECT_URL);
        $events = $request->attributes->get(static::URL_PARAM_EVENTS);

        $eventItemTriggerDataProvider = $this->getFactory()->createEventItemTriggerFormDataProvider();

        $eventTriggerFormCollection = [];
        foreach ($events as $event) {
            $eventTriggerFormCollection[$event] = $this->getFactory()
                ->createEventItemTriggerForm($eventItemTriggerDataProvider->getOptions($merchantSalesOrderItemReference, $event, $redirect))
                ->createView();
        }

        return $this->viewResponse([
            'formCollection' => $eventTriggerFormCollection,
        ]);
    }
}
