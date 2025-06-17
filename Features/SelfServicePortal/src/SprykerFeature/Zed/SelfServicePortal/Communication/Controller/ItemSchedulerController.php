<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 */
class ItemSchedulerController extends AbstractController
{
    /**
     * @var string
     */
    protected const PARAM_ID_SALES_ORDER_ITEM = 'id-sales-order-item';

    /**
     * @var string
     */
    protected const MESSAGE_ITEM_UPDATED_SUCCESS = 'Order item scheduled successfully.';

    /**
     * @uses \Spryker\Zed\Sales\Communication\Controller\DetailController::indexAction()
     *
     * @var string
     */
    protected const REDIRECT_URL_PATH_DETAIL = '/sales/detail';

    /**
     * @var string
     */
    protected const PARAM_ID_SALES_ORDER = 'id-sales-order';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    public function indexAction(Request $request): array|RedirectResponse
    {
        $idSalesOrderItem = $this->castId($request->query->get(static::PARAM_ID_SALES_ORDER_ITEM));

        $itemTransfer = $this->getFactory()->createSalesOrderItemReader()->findOrderItemById($idSalesOrderItem);
        if (!$itemTransfer) {
            throw new NotFoundHttpException(sprintf(
                'Sales order item with id %s not found.',
                $idSalesOrderItem,
            ));
        }

        $itemSchedulerForm = $this->getFactory()->createItemSchedulerForm($itemTransfer);
        $itemSchedulerForm->handleRequest($request);

        if (!$itemSchedulerForm->isSubmitted() || !$itemSchedulerForm->isValid()) {
            return [
                'itemSchedulerForm' => $itemSchedulerForm->createView(),
                'orderItem' => $itemTransfer,
            ];
        }

        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $itemSchedulerForm->getData();

        $salesOrderItemCollectionRequestTransfer = (new SalesOrderItemCollectionRequestTransfer())
            ->setItems(new ArrayObject([$itemTransfer]));

        $salesOrderItemCollectionResponseTransfer = $this->getFacade()->updateSalesOrderItemCollection(
            $salesOrderItemCollectionRequestTransfer,
        );

        if ($salesOrderItemCollectionResponseTransfer->getErrors()->count()) {
            foreach ($salesOrderItemCollectionResponseTransfer->getErrors() as $errorTransfer) {
                $this->addErrorMessage($errorTransfer->getMessageOrFail(), $errorTransfer->getParameters());
            }

            return [
                'itemSchedulerForm' => $itemSchedulerForm->createView(),
                'orderItem' => $itemTransfer,
            ];
        }

        $this->addSuccessMessage(static::MESSAGE_ITEM_UPDATED_SUCCESS);

        return $this->redirectResponse(
            sprintf('%s?%s=%d', static::REDIRECT_URL_PATH_DETAIL, static::PARAM_ID_SALES_ORDER, $itemTransfer->getFkSalesOrderOrFail()),
        );
    }
}
