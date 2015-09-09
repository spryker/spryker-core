<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Refund\Communication\Controller;


use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Refund\Business\RefundFacade;
use SprykerFeature\Zed\Refund\Communication\RefundDependencyContainer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method RefundDependencyContainer getDependencyContainer()
 * @method RefundFacade getFacade()
 */
class IndexController extends AbstractController
{

    public function indexAction(Request $request)
    {
        $idOrder = $request->query->get('id-sales-order');
        /*
        $manager = $this->getDependencyContainer()
            ->createRefundManager()
        ;
        */

        $form = $this->getDependencyContainer()
            ->createRefundForm()
        ;

        $form->handleRequest();

        if ($form->isValid()) {
            //$refundTransfer = (new RefundTransfer())->fromArray($form->getData(), true);
            $this->getFacade()
                //
            ;

            return $this->redirectResponse(sprintf('/sales/details/?id-sales-order=%d', $idOrder));
        }

        return $this->viewResponse([
            'idOrder' => $idOrder,
            'form' => $form->createView(),
        ]);
    }

}
