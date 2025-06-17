<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 */
class ViewServiceController extends AbstractController
{
    /**
     * @var string
     */
    public const REQUEST_PARAM_ID_SALES_ORDER_ITEM = 'id-sales-order-item';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_SERVICE_DOES_NOT_EXIST = 'Service with ID "%id%" does not exist.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_PARAMETER_ID = '%id%';

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\ListServiceController::indexAction()
     *
     * @var string
     */
    protected const URL_PATH_SELF_SERVICE_PORTAL_LIST_SERVICE = '/self-service-portal/list-service';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    public function indexAction(Request $request): array|RedirectResponse
    {
        $idSalesOrderItem = $this->castId($request->get(static::REQUEST_PARAM_ID_SALES_ORDER_ITEM));

        $itemTransfer = $this->getFactory()->createSalesOrderItemReader()->findOrderItemById($idSalesOrderItem);
        if (!$itemTransfer) {
            $this->addErrorMessage(static::ERROR_MESSAGE_SERVICE_DOES_NOT_EXIST, [
                static::ERROR_MESSAGE_PARAMETER_ID => $idSalesOrderItem,
            ]);

            return $this->redirectResponse(static::URL_PATH_SELF_SERVICE_PORTAL_LIST_SERVICE);
        }

        return $this->viewResponse([
            'orderItem' => $itemTransfer,
            'urlPathSelfServicePortalList' => static::URL_PATH_SELF_SERVICE_PORTAL_LIST_SERVICE,
        ]);
    }
}
