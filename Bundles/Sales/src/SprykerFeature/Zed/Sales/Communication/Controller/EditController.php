<?php

namespace SprykerFeature\Zed\Sales\Communication\Controller;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Sales\Communication\Form\CustomerForm;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method SalesDependencyContainer getDependencyContainer()
 */
class EditController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function customerAction(Request $request)
    {
        $idOrder = $request->get('id');
        $form = $this->getDependencyContainer()->createCustomerForm($idOrder);
        $form->init();
        $form->handleRequest();

        if ($request->isMethod('POST') && $form->isValid()) {
            $data = $form->getData();

            $orderEntity = $this->getQueryContainer()
                ->querySalesOrderById($idOrder)
                ->findOne()
            ;

            $orderEntity->setFirstName($data[CustomerForm::FIRST_NAME]);
            $orderEntity->setLastName($data[CustomerForm::LAST_NAME]);
            $orderEntity->setEmail($data[CustomerForm::EMAIL]);
            $orderEntity->setSalutation($data[CustomerForm::SALUTATION]);

            $orderEntity->save();

            return $this->redirectResponse(sprintf('/sales/edit/customer?id=%d', $idOrder));
        }

        return $this->viewResponse([
            'idOrder' => $idOrder,
            'form' => $form->createView(),
        ]);
    }
}
