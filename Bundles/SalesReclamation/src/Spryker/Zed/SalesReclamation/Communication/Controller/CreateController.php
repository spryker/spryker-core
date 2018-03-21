<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Communication\Controller;

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
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idSalesOrder = $this->castId($request->query->getInt(SalesReclamationConfig::PARAM_ID_SALES_ORDER));

        $orderTransfer = $this
            ->getFactory()
            ->getSalesFacade()
            ->getOrderByIdSalesOrder($idSalesOrder);

        return $this->viewResponse([
            'test' => 'Greetings!',
            'idSalesOrder' => $idSalesOrder,
            'order' => $orderTransfer,
        ]);
    }
}
