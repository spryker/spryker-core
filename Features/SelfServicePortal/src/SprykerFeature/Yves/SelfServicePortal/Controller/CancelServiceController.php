<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Controller;

use Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer;
use SprykerFeature\Yves\SelfServicePortal\Plugin\Router\SelfServicePortalPageRouteProviderPlugin;
use SprykerFeature\Yves\SelfServicePortal\Service\Form\SspServiceCancelForm;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Client\SelfServicePortal\SelfServicePortalClientInterface getClient()
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalFactory getFactory()
 */
class CancelServiceController extends AbstractController
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_CANCELLATION_ERROR = 'self_service_portal.service.cancellation.error';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_CANCELLATION_SUCCESS = 'self_service_portal.service.cancellation.success';

    public function cancelServiceAction(Request $request): RedirectResponse
    {
        $sspServiceCancelForm = $this->getFactory()->getSspServiceCancelForm();
        $sspServiceCancelForm->handleRequest($request);

        $response = $this->redirectResponseInternal(SelfServicePortalPageRouteProviderPlugin::ROUTE_NAME_SSP_SERVICE_LIST);

        if (!$sspServiceCancelForm->isSubmitted() || !$sspServiceCancelForm->isValid()) {
            $this->addErrorMessage(static::GLOSSARY_KEY_CANCELLATION_ERROR);

            return $response;
        }

        $formData = $sspServiceCancelForm->getData();
        $orderItemUuid = $formData[SspServiceCancelForm::FIELD_ITEM_UUID];
        $idSalesOrder = $formData[SspServiceCancelForm::FIELD_ID_SALES_ORDER];

        $itemTransfer = $this->getFactory()
            ->createOrderReader()
            ->getOrderItem((int)$idSalesOrder, $orderItemUuid);

        $salesOrderItemCollectionRequestTransfer = new SalesOrderItemCollectionRequestTransfer();
        $salesOrderItemCollectionRequestTransfer->addItem($itemTransfer);

        $salesOrderItemCollectionResponseTransfer = $this->getClient()
            ->cancelSalesOrderItemCollection($salesOrderItemCollectionRequestTransfer);

        if ($salesOrderItemCollectionResponseTransfer->getErrors()->count() > 0) {
            $this->addErrorMessage(static::GLOSSARY_KEY_CANCELLATION_ERROR);

            return $response;
        }

        $this->addSuccessMessage(static::GLOSSARY_KEY_CANCELLATION_SUCCESS);

        return $response;
    }
}
