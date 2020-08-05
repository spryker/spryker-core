<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantSalesOrderGui\Communication\MerchantSalesOrderGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantSalesOrderGui\Business\MerchantSalesOrderGuiFacadeInterface getFacade()
 */
class OmsTriggerController extends AbstractController
{
    protected const URL_PARAM_ID_MERCHANT_SALES_ORDER_ITEM = 'id-merchant-sales-order-item';
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

        $this->addInfoMessage(static::MESSAGE_STATUS_CHANGED_SUCCESSFULLY);

        return $this->redirectResponse($redirect);
    }
}
