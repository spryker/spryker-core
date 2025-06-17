<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Controller;

use Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer;
use Spryker\Yves\Kernel\View\View;
use SprykerFeature\Yves\SelfServicePortal\Plugin\Router\SelfServicePortalPageRouteProviderPlugin;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerFeature\Client\SelfServicePortal\SelfServicePortalClientInterface getClient()
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalFactory getFactory()
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class UpdateServiceTimeController extends AbstractController
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_UPDATE_SCHEDULED_TIME_SUCCESS = 'self_service_portal.service.update_scheduled_time.success';

    /**
     * @var string
     */
    protected const PARAM_ITEM_UUID = 'item-uuid';

    /**
     * @var string
     */
    protected const PARAM_ORDER_ID = 'id-sales-order';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateServiceTimeAction(Request $request): View|RedirectResponse
    {
        if ($this->getFactory()->getCustomerClient()->isLoggedIn() === false) {
            throw new NotFoundHttpException();
        }

        $orderItemUuid = $request->get(static::PARAM_ITEM_UUID);
        $idSalesOrder = $request->get(static::PARAM_ORDER_ID);

        $itemTransfer = $this->getFactory()
            ->createOrderReader()
            ->getOrderItem((int)$idSalesOrder, $orderItemUuid);

        $serviceItemSchedulerForm = $this->getFactory()->createServiceItemSchedulerForm($itemTransfer);
        $serviceItemSchedulerForm->handleRequest($request);

        if ($serviceItemSchedulerForm->isSubmitted() && $serviceItemSchedulerForm->isValid()) {
            $itemTransfer = $serviceItemSchedulerForm->getData();

            $salesOrderItemCollectionRequestTransfer = new SalesOrderItemCollectionRequestTransfer();
            $salesOrderItemCollectionRequestTransfer->addItem($itemTransfer);

            $salesOrderItemCollectionResponseTransfer = $this->getClient()
                ->updateSalesOrderItemCollection($salesOrderItemCollectionRequestTransfer);

            if ($salesOrderItemCollectionResponseTransfer->getErrors()->count() === 0) {
                $this->addSuccessMessage(static::GLOSSARY_KEY_UPDATE_SCHEDULED_TIME_SUCCESS);

                return $this->redirectResponseInternal(SelfServicePortalPageRouteProviderPlugin::ROUTE_NAME_SSP_SERVICE_LIST);
            }
        }

        return $this->view(
            [
                'orderItem' => $itemTransfer,
                'serviceItemSchedulerForm' => $serviceItemSchedulerForm->createView(),
            ],
            [],
            '@SelfServicePortal/views/update-service-time/update-service-time.twig',
        );
    }
}
