<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Refund\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\Refund\Communication\RefundCommunicationFactory getFactory()
 * @method \Spryker\Zed\Refund\Persistence\RefundQueryContainer getQueryContainer()
 * @method \Spryker\Zed\Refund\Business\RefundFacade getFacade()
 */
class TableController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        $table = $this->getFactory()->createRefundTable();

        return $this->viewResponse(['refunds' => $table->render()]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getFactory()->createRefundTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }
}
