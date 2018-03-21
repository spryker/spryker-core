<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Communication\Controller;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\SalesReclamation\SalesReclamationConfig;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\SalesReclamation\Business\SalesReclamationFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesReclamation\Communication\SalesReclamationCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesReclamation\Persistence\SalesReclamationQueryContainerInterface getQueryContainer()
 */
class CreateController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idSalesOrder = $this->castId($request->get(SalesReclamationConfig::PARAM_ID_SALES_ORDER));

        $orderTransfer = $this
            ->getFactory()
            ->getSalesFacade()
            ->getOrderByIdSalesOrder($idSalesOrder);

        if ($request->isMethod(Request::METHOD_GET)) {
            return $this->showForm($orderTransfer);
        }

        $idsOrderItem = $request->request->getDigits(SalesReclamationConfig::PARAM_IDS_SALES_ORDER_ITEMS);

        if (!$idsOrderItem) {
            $this->addErrorMessage('No order items provided');

            return $this->showForm($orderTransfer);
        }

        $reclamationTransfer = $this->getFacade()->createReclamation($orderTransfer, ...$idsOrderItem);

        $this->addSuccessMessage(sprintf(
            'Reclamation id:%s for order %s sucessfully created',
            $reclamationTransfer->getIdSalesReclamation(),
            $orderTransfer->getOrderReference()
        ));

        return $this->redirectResponse(
            Url::generate(
                '/sales-reclamation'
            )->build()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    protected function showForm(OrderTransfer $orderTransfer)
    {
        return $this->viewResponse([
            'order' => $orderTransfer,
        ]);
    }
}
