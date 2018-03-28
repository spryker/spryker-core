<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OfferGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \Spryker\Zed\OfferGui\Communication\OfferGuiCommunicationFactory getFactory()
 */
class ViewController extends AbstractController
{
    public const PARAM_ID_SALES_ORDER = 'id-sales-order';

    /**
     * @return array
     */
    public function listAction()
    {
        return $this->viewResponse([
            'offers' => $this->getFactory()
                ->createOffersTable()
                ->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getFactory()->createOffersTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function detailsAction(Request $request)
    {
        $idSalesOrder = $this->castId($request->query->getInt(static::PARAM_ID_SALES_ORDER));

        $orderTransfer = $this->getFactory()->getSalesFacade()->getOrderByIdSalesOrder($idSalesOrder);

        if ($orderTransfer->getType() !== $this->getFactory()->getConfig()->getOrderTypeOffer()) {
            throw new NotFoundHttpException();
        }

        return $this->viewResponse([
            'offer' => $orderTransfer,
        ]);
    }
}
