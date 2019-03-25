<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Communication\Controller;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\Sales\SalesConfig;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Sales\Communication\SalesCommunicationFactory getFactory()
 * @method \Spryker\Zed\Sales\Business\SalesFacadeInterface getFacade()
 * @method \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface getRepository()
 */
class EditController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function customerAction(Request $request)
    {
        $idSalesOrder = $this->castId($request->query->get(SalesConfig::PARAM_ID_SALES_ORDER));

        $dataProvider = $this->getFactory()->createCustomerFormDataProvider();
        $form = $this->getFactory()
            ->getCustomerForm(
                $dataProvider->getData($idSalesOrder),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $orderTransfer = (new OrderTransfer())->fromArray($form->getData(), true);
            $this->getFacade()->updateOrder($orderTransfer, $idSalesOrder);

            $this->addSuccessMessage('Customer successfully updated.');

            return $this->redirectResponse(
                Url::generate(
                    '/sales/detail',
                    [
                        SalesConfig::PARAM_ID_SALES_ORDER => $idSalesOrder,
                    ]
                )->build()
            );
        }

        return $this->viewResponse([
            'idSalesOrder' => $idSalesOrder,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addressAction(Request $request)
    {
        $idSalesOrder = $this->castId($request->query->get(SalesConfig::PARAM_ID_SALES_ORDER));
        $idOrderAddress = $this->castId($request->query->get('id-address'));

        $dataProvider = $this->getFactory()->createAddressFormDataProvider();
        $form = $this->getFactory()
            ->getAddressForm(
                $dataProvider->getData($idOrderAddress),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $addressTransfer = (new AddressTransfer())->fromArray($form->getData(), true);
            $addressTransfer->setIdSalesOrderAddress($idOrderAddress);
            $this->getFacade()
                ->updateOrderAddress($addressTransfer, $idOrderAddress);

            $this->addSuccessMessage('Address successfully updated.');

            return $this->redirectResponse(
                Url::generate(
                    '/sales/detail',
                    [
                        SalesConfig::PARAM_ID_SALES_ORDER => $idSalesOrder,
                    ]
                )->build()
            );
        }

        return $this->viewResponse([
            'idSalesOrder' => $idSalesOrder,
            'form' => $form->createView(),
        ]);
    }
}
