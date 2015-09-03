<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Refund\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Refund\Business\RefundFacade;
use SprykerFeature\Zed\Refund\Communication\RefundDependencyContainer;

/**
 * @method RefundDependencyContainer getDependencyContainer()
 * @method RefundFacade getFacade()
 */
class IndexController extends AbstractController
{

    public function indexAction()
    {
        $manager = $this->getDependencyContainer()
            ->createRefundManager()
        ;


    }

}
