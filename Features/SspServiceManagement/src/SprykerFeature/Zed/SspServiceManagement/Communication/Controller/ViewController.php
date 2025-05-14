<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspServiceManagement\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SspServiceManagement\Communication\SspServiceManagementCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SspServiceManagement\Business\SspServiceManagementFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SspServiceManagement\Persistence\SspServiceManagementRepositoryInterface getRepository()
 */
class ViewController extends AbstractController
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
     * @uses \SprykerFeature\Zed\SspServiceManagement\Communication\Controller\ListController::indexAction()
     *
     * @var string
     */
    protected const URL_SSP_SERVICE_MANAGEMENT_LIST = '/ssp-service-management/list';

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

            return $this->redirectResponse(static::URL_SSP_SERVICE_MANAGEMENT_LIST);
        }

        return $this->viewResponse([
            'orderItem' => $itemTransfer,
            'urlSspServiceManagementList' => static::URL_SSP_SERVICE_MANAGEMENT_LIST,
        ]);
    }
}
