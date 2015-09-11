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

        $table = $this->getDependencyContainer()->createRefundsTable();

        return [
            'orders' => $table->render(),
        ];
    }

    /**
     * @return JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getDependencyContainer()->createRefundsTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

    public function addAction(Request $request)
    {
        $idOrder = $request->query->get('id-sales-order');

        $form = $this->getDependencyContainer()
            ->createRefundForm($request)
        ;

        $form->handleRequest();

        if ($form->isValid()) {
            $formData = $form->getData();

            dump($formData);
            die;
            //$refundTransfer = (new RefundTransfer())->fromArray($form->getData(), true);
//            $this->getFacade()
//                //
//            ;

            return $this->redirectResponse(sprintf('/sales/details/?id-sales-order=%d', $idOrder));
        }

        return $this->viewResponse([
            'idOrder' => $idOrder,
            'form' => $form->createView(),
        ]);
    }
}
