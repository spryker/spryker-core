<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Refund\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\Refund\Communication\RefundCommunicationFactory getFactory()
 * @method \Spryker\Zed\Refund\Persistence\RefundQueryContainer getQueryContainer()
 * @method \Spryker\Zed\Refund\Business\RefundFacade getFacade()
 */
class SalesController extends AbstractController
{

    /**
     * @return array
     */
    public function listAction()
    {
        $table = $this->getFactory()->createRefundTable($this->getFacade());

        return $this->viewResponse(['refunds' => $table->render()]);
    }
}
